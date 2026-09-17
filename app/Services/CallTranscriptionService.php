<?php

namespace App\Services;

use App\Models\CallLog;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

/**
 * Sarvam speech-to-text for lead call recordings, split into a quick start
 * step and a single status check so it works without a queue worker.
 */
class CallTranscriptionService
{
    private const BASE_URL = 'https://api.sarvam.ai/speech-to-text/job/v1';

    /** Upload the recording and start the Sarvam job (a few seconds). */
    public function start(CallLog $callLog): void
    {
        try {
            $client = $this->client();
            $callLog->update(['transcription_status' => 'processing', 'transcription_error' => null, 'sarvam_job_id' => null]);

            $audio = Http::withBasicAuth(config('services.plivo.auth_id'), config('services.plivo.auth_token'))
                ->connectTimeout(10)->timeout(60)->get($callLog->recording_url)->throw();
            $contentType = $audio->header('Content-Type') ?: 'audio/mpeg';
            $fileName = 'call-'.$callLog->id.'.'.(str_contains($contentType, 'wav') ? 'wav' : 'mp3');

            $jobId = $client->post(self::BASE_URL, [
                'job_parameters' => [
                    'model' => config('services.sarvam.model'),
                    'mode' => config('services.sarvam.lead_call_mode', 'translit'),
                    'language_code' => config('services.sarvam.language_code'),
                    'with_diarization' => true,
                    'num_speakers' => 2,
                ],
            ])->throw()->json('job_id');
            if (! $jobId) throw new RuntimeException('Transcription service did not return a job ID.');

            $upload = $client->post(self::BASE_URL.'/upload-files', ['job_id' => $jobId, 'files' => [$fileName]])->throw()->json();
            // File names contain a dot, so data_get() dot notation cannot be used here.
            $uploadUrl = $upload['upload_urls'][$fileName]['file_url'] ?? null;
            if (! $uploadUrl) throw new RuntimeException('Transcription service did not return an upload URL.');

            Http::withHeaders(['x-ms-blob-type' => 'BlockBlob', 'Content-Type' => $contentType])
                ->withBody($audio->body(), $contentType)->timeout(60)->put($uploadUrl)->throw();
            $client->post(self::BASE_URL."/{$jobId}/start", [])->throw();

            $callLog->update(['sarvam_job_id' => $jobId]);
        } catch (Throwable $exception) {
            $this->fail($callLog, $exception);
        }
    }

    /**
     * Check the job once and store the transcript when it is ready.
     * Returns the current transcription status.
     */
    public function sync(CallLog $callLog): ?string
    {
        if ($callLog->transcription_status !== 'processing' || ! $callLog->sarvam_job_id) {
            return $callLog->transcription_status;
        }

        try {
            $client = $this->client();
            $jobId = $callLog->sarvam_job_id;
            $status = $client->get(self::BASE_URL."/{$jobId}/status")->throw()->json();
            $state = $status['job_state'] ?? '';

            if ($state === 'Failed') {
                throw new RuntimeException($status['error_message'] ?? 'Transcription failed.');
            }
            if (! in_array($state, ['Completed', 'PartiallyCompleted'], true)) {
                return 'processing';
            }

            $outputFile = data_get($status, 'job_details.0.outputs.0.file_name');
            if (! $outputFile) throw new RuntimeException('Transcription service returned no output file.');
            $download = $client->post(self::BASE_URL.'/download-files', ['job_id' => $jobId, 'files' => [$outputFile]])->throw()->json();
            $downloadUrl = $download['download_urls'][$outputFile]['file_url'] ?? null;
            if (! $downloadUrl) throw new RuntimeException('Transcription service did not return a download URL.');
            $result = Http::timeout(30)->get($downloadUrl)->throw()->json();

            $callLog->update([
                'transcription_status' => 'completed',
                'transcript' => $result['transcript'] ?? null,
                'diarized_transcript' => $result['diarized_transcript'] ?? null,
                'transcription_error' => null,
            ]);
        } catch (Throwable $exception) {
            $this->fail($callLog, $exception);
        }

        return $callLog->transcription_status;
    }

    private function client()
    {
        $apiKey = config('services.sarvam.api_key');
        if (! $apiKey) throw new RuntimeException('SARVAM_API_KEY is not configured.');

        return Http::withHeaders(['api-subscription-key' => $apiKey])->acceptJson()->connectTimeout(10)->timeout(30);
    }

    private function fail(CallLog $callLog, Throwable $exception): void
    {
        report($exception);
        $callLog->update([
            'transcription_status' => 'failed',
            'transcription_error' => mb_substr($exception->getMessage(), 0, 2000),
        ]);
    }
}
