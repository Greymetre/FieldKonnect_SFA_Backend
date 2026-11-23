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
            <div class="draft-approver-row">
                <label>Draft for (contact) <span style="color:red">*</span></label>
                <select name="lead_contact_id" class="form-control draft-contact" required>
                    <option value="">Choose the contact this draft is for</option>
                    @foreach($lead_contacts as $draftContact)
                    <option value="{{ $draftContact->id }}" {{ $lead_contacts->count() === 1 ? 'selected' : '' }}>{{ $draftContact->name ?: 'Contact' }}{{ $draftContact->title ? ' · '.$draftContact->title : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="draft-feedback-banner" style="display:none;"></div>
            <div class="draft-approver-row">
                <label>Send for approval to <span style="color:red">*</span></label>
                <select name="approver_id" class="form-control draft-approver" required>
                    <option value="">Choose who should approve this draft</option>
                    @foreach($draft_approvers as $approverUser)
                    <option value="{{ $approverUser->id }}">{{ $approverUser->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="draft-form-actions">
                <button type="button" class="btn btn-outline-primary draft-cancel">Cancel</button>
                <button type="submit" class="btn btn-primary">Save draft</button>
            </div>
        </form>

        @if($drafts->isEmpty())
        <p class="lead-empty">No {{ strtolower($title) }}s yet.</p>
        @endif

        @foreach($drafts as $draft)
        <div class="draft-item {{ $draft->isApproved() ? 'is-approved' : '' }}" id="draft-item-{{ $draft->id }}">
            <div class="draft-icon"><i class="material-icons">{{ $icon }}</i></div>
            <div class="draft-main">
                <div class="draft-text-view" id="draft-text-{{ $draft->id }}">{{ $draft->note }}</div>
                <div class="draft-meta">
                    @if($draft->isApproved())
                    <span class="draft-status is-approved" title="Approved by {{ $draft->approvedby->name ?? '-' }} on {{ $draft->approved_at->format('d M Y, h:i A') }}"><i class="material-icons">verified</i> Approved</span>
                    @elseif($draft->needsChanges())
                    <span class="draft-status is-changes"><i class="material-icons">rate_review</i> Changes requested</span>
                    @else
                    <span class="draft-status is-pending"><i class="material-icons">schedule</i> Pending approval</span>
                    @endif
                    @if($draft->contact)
                    <span class="draft-for"><i class="material-icons">person</i> For {{ $draft->contact->name }}</span>
                    @endif
                    {{ $draft->createdby->name ?? 'Unknown' }} · {{ $draft->created_at?->format('d M Y, h:i A') }}
                    @if($draft->approver)
                    · approval: {{ $draft->approver->name }}
                    @endif
                    @if($draft->isApproved())
                    · approved by {{ $draft->approvedby->name ?? '-' }}
                    @endif
                </div>
                @php
                    $lastFeedback = $draft->feedbacks->last();
                @endphp
                @if($lastFeedback && ! $draft->isApproved())
                <div class="draft-feedback {{ $draft->needsChanges() ? 'is-open' : '' }}">
                    <div class="draft-feedback-head"><i class="material-icons">rate_review</i> Feedback from {{ $lastFeedback->user->name ?? 'approver' }} · {{ $lastFeedback->created_at?->format('d M Y, h:i A') }}</div>
                    <p>{{ $lastFeedback->feedback }}</p>
                    @if($draft->needsChanges())
                    <small>Edit the draft as per this feedback and save — it goes back to {{ $draft->approver->name ?? 'the approver' }} for approval.</small>
                    @else
                    <small>Updated and sent back for approval.</small>
                    @endif
                </div>
                @endif
            </div>
            <div class="draft-actions">
                @if($type === 'linkedin' && $draft->isApproved())
                <button type="button" class="draft-send-btn draft-linkedin-btn" data-draft-target="#draft-text-{{ $draft->id }}" data-contact-url="{{ $draft->contact->url ?? '' }}" title="Send this LinkedIn message">
                    <i class="material-icons">send</i> Send message
                </button>
                @endif
                @if($type === 'email' && $draft->isApproved())
                <button type="button" class="draft-send-btn draft-email-btn" data-draft-target="#draft-text-{{ $draft->id }}" data-contact-email="{{ trim($draft->contact->email ?? '') }}" title="Send this email">
                    <i class="material-icons">send</i> Send email
                </button>
                @endif
                <button type="button" class="contact-icon-btn draft-copy" data-copy-target="#draft-text-{{ $draft->id }}" title="Copy draft" data-toggle="tooltip"><i class="material-icons">content_copy</i></button>
                {{-- Approved drafts are locked; only approvers may still change them. --}}
                @if(! $draft->isApproved() || $draft->canBeApprovedBy(auth()->user()))
                <button type="button" class="contact-icon-btn contact-icon-muted draft-edit" data-draft-form="#{{ $paneId }}-form" data-id="{{ $draft->id }}" data-approver="{{ $draft->approver_id }}" data-contact="{{ $draft->lead_contact_id }}" data-feedback="{{ $draft->needsChanges() ? ($lastFeedback->feedback ?? '') : '' }}" data-text="{{ $draft->note }}" title="Edit draft" data-toggle="tooltip"><i class="material-icons">edit</i></button>
                <form action="{{ route('lead-notes.destroy', $draft->id) }}" method="POST" class="d-inline-flex m-0" onsubmit="return confirm('Delete this draft?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="contact-icon-btn contact-icon-muted contact-icon-danger" title="Delete draft" data-toggle="tooltip"><i class="material-icons">delete</i></button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
