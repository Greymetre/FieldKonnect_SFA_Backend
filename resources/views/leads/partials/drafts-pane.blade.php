{{--
    Email / LinkedIn draft tab. Works like Notes: the agent writes a draft,
    it is saved on the lead and also shows up in Activities.
    Params: $paneId, $type, $title, $icon, $placeholder, $drafts, $lead
--}}
<div class="tab-pane fade" id="{{ $paneId }}" role="tabpanel">
    <div class="card-header d-flex justify-content-between align-items-center">
        <p class="hed m-0">{{ $title }}s</p>
        <button type="button" class="draft-new-btn" data-draft-form="#{{ $paneId }}-form">
            <i class="material-icons">add</i> New {{ strtolower($title) }}
        </button>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('lead-notes.store') }}" class="draft-form" id="{{ $paneId }}-form" style="display:none;">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="note_id" class="draft-id">
            <textarea name="note" class="form-control draft-text" rows="7" placeholder="{{ $placeholder }}" required></textarea>
            <div class="draft-form-actions">
                <button type="button" class="btn btn-outline-primary draft-cancel">Cancel</button>
                <button type="submit" class="btn btn-primary">Save draft</button>
            </div>
        </form>

        @if($drafts->isEmpty())
        <p class="lead-empty">No {{ strtolower($title) }}s yet.</p>
        @endif

        @foreach($drafts as $draft)
        <div class="draft-item">
            <div class="draft-icon"><i class="material-icons">{{ $icon }}</i></div>
            <div class="draft-main">
                <div class="draft-text-view" id="draft-text-{{ $draft->id }}">{{ $draft->note }}</div>
                <div class="draft-meta">
                    {{ $draft->createdby->name ?? 'Unknown' }} · {{ $draft->created_at?->format('d M Y, h:i A') }}
                </div>
            </div>
            <div class="draft-actions">
                <button type="button" class="contact-icon-btn draft-copy" data-copy-target="#draft-text-{{ $draft->id }}" title="Copy draft" data-toggle="tooltip"><i class="material-icons">content_copy</i></button>
                <button type="button" class="contact-icon-btn contact-icon-muted draft-edit" data-draft-form="#{{ $paneId }}-form" data-id="{{ $draft->id }}" data-text="{{ $draft->note }}" title="Edit draft" data-toggle="tooltip"><i class="material-icons">edit</i></button>
                <form action="{{ route('lead-notes.destroy', $draft->id) }}" method="POST" class="d-inline-flex m-0" onsubmit="return confirm('Delete this draft?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="contact-icon-btn contact-icon-muted contact-icon-danger" title="Delete draft" data-toggle="tooltip"><i class="material-icons">delete</i></button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
