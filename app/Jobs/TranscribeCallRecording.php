<?php

namespace App\Jobs;

use App\Models\CallLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class TranscribeCallRecording implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries = 3;
    public array $backoff = [60, 180];

    public function __construct(public int $callLogId)
    {
    }

    public function handle(): void
    {
        $callLog = CallLog::find($this->callLogId);
        if (! $callLog || ! $callLog->recording_url || $callLog->transcription_status === 'completed') {
            return;
        }

        $apiKey = config('services.sarvam.api_key');
        if (! $apiKey) {
            throw new RuntimeException('SARVAM_API_KEY is not configured.');
        }

        $callLog->update(['transcription_status' => 'processing', 'transcription_error' => null]);

        $audio = Http::withBasicAuth(config('services.plivo.auth_id'), config('services.plivo.auth_token'))
            ->connectTimeout(10)->timeout(120)->get($callLog->recording_url)->throw();
        $contentType = $audio->header('Content-Type') ?: 'audio/mpeg';
        $extension = str_contains($contentType, 'wav') ? 'wav' : 'mp3';
        $fileName = 'call-'.$callLog->id.'.'.$extension;

        $client = Http::withHeaders(['api-subscription-key' => $apiKey])->acceptJson()->timeout(60);
        $job = $client->post('https://api.sarvam.ai/speech-to-text/job/v1', [
            'job_parameters' => [
                'model' => config('services.sarvam.model'),
                'mode' => config('services.sarvam.mode'),
                'language_code' => config('services.sarvam.language_code'),
                'with_diarization' => true,
                'num_speakers' => 2,
            ],
        ])->throw()->json();
        $jobId = $job['job_id'] ?? null;
        if (! $jobId) throw new RuntimeException('Sarvam did not return a job ID.');
        $callLog->update(['sarvam_job_id' => $jobId]);

        $upload = $client->post('https://api.sarvam.ai/speech-to-text/job/v1/upload-files', [
            'job_id' => $jobId,
            'files' => [$fileName],
        ])->throw()->json();
        // Filenames contain a dot (for example, call-123.mp3), so direct array
        // access is required; data_get() would treat the dot as path notation.
        $uploadUrl = $upload['upload_urls'][$fileName]['file_url'] ?? null;
        if (! $uploadUrl) throw new RuntimeException('Sarvam did not return an upload URL.');

        Http::withHeaders(['x-ms-blob-type' => 'BlockBlob', 'Content-Type' => $contentType])
            ->withBody($audio->body(), $contentType)->timeout(120)->put($uploadUrl)->throw();
        $client->post("https://api.sarvam.ai/speech-to-text/job/v1/{$jobId}/start", [])->throw();

        $status = [];
        for ($attempt = 0; $attempt < 40; $attempt++) {
            sleep(10);
            $status = $client->get("https://api.sarvam.ai/speech-to-text/job/v1/{$jobId}/status")->throw()->json();
            if (in_array($status['job_state'] ?? '', ['Completed', 'PartiallyCompleted', 'Failed'], true)) break;
        }
        if (! in_array($status['job_state'] ?? '', ['Completed', 'PartiallyCompleted'], true)) {
            throw new RuntimeException($status['error_message'] ?? 'Sarvam transcription timed out or failed.');
        }

        $outputFile = data_get($status, 'job_details.0.outputs.0.file_name');
        if (! $outputFile) throw new RuntimeException('Sarvam returned no transcript output file.');
        $download = $client->post('https://api.sarvam.ai/speech-to-text/job/v1/download-files', [
            'job_id' => $jobId,
            'files' => [$outputFile],
        ])->throw()->json();
        $downloadUrl = $download['download_urls'][$outputFile]['file_url'] ?? null;
        if (! $downloadUrl) throw new RuntimeException('Sarvam did not return a transcript download URL.');
        $result = Http::timeout(60)->get($downloadUrl)->throw()->json();

        $callLog->update([
            'transcription_status' => 'completed',
            'transcript' => $result['transcript'] ?? null,
            'diarized_transcript' => $result['diarized_transcript'] ?? null,
            'transcription_error' => null,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        CallLog::whereKey($this->callLogId)->update([
            'transcription_status' => 'failed',
            'transcription_error' => mb_substr($exception->getMessage(), 0, 2000),
        ]);
    }
}
