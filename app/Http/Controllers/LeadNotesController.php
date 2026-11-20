<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

use DataTables;
use Auth;

use App\Models\Lead;
use App\Models\LeadNote;

class LeadNotesController extends Controller
{
    

  


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
    {
        $existing = $request->note_id ? LeadNote::where('id', $request->note_id)->first() : null;
        $type = $existing->type ?? $request->input('type', LeadNote::TYPE_NOTE);
        $isDraft = in_array($type, [LeadNote::TYPE_EMAIL, LeadNote::TYPE_LINKEDIN], true);

        $request->validate([
            'lead_id' => 'required',
            'note' => 'required',
            'type' => 'nullable|in:'.implode(',', LeadNote::TYPES),
            // Drafts go to the user picked here for approval.
            'approver_id' => [$isDraft ? 'required' : 'nullable', 'integer', 'exists:users,id'],
        ], ['approver_id.required' => 'Please choose who should approve this draft.']);

        $labels = [
            LeadNote::TYPE_NOTE => ['Lead Note', 'notes'],
            LeadNote::TYPE_EMAIL => ['Email draft', 'email-drafts'],
            LeadNote::TYPE_LINKEDIN => ['LinkedIn draft', 'linkedin-drafts'],
        ];
        [$label, $tab] = $labels[$type] ?? $labels[LeadNote::TYPE_NOTE];

        if ($existing && $existing->isApproved() && ! $existing->canBeApprovedBy(Auth::user())) {
            return redirect()->back()->with('lead_tab', $tab)
                ->withErrors(['note' => 'This draft is approved and can no longer be edited.']);
        }

        $approverId = $isDraft ? (int) $request->approver_id : null;
        if ($existing) {
            $updates = ['note' => $request->note, 'lead_id' => $request->lead_id];
            if ($isDraft) {
                $updates['approver_id'] = $approverId;
            } else {
                $updates['created_by'] = Auth::id();
            }
            $approverChanged = $isDraft && (int) $existing->approver_id !== $approverId;
            $existing->update($updates);
            $request->session()->flash('message_success', $label.' updated successfully.');
            if ($approverChanged && ! $existing->isApproved()) {
                $this->notifyApprover($existing, $label);
            }
        } else {
            $draft = LeadNote::create([
                'type' => $type,
                'note' => $request->note,
                'lead_id' => $request->lead_id,
                'created_by' => Auth::id(),
                'approver_id' => $approverId,
            ]);
            $request->session()->flash('message_success', $isDraft
                ? $label.' sent to '.($draft->approver->name ?? 'the approver').' for approval.'
                : $label.' added successfully.');
            if ($isDraft) {
                $this->notifyApprover($draft, $label);
            }
        }

        // Reopen the tab the entry was saved from.
        return redirect()->back()->with('lead_tab', $tab);
    }

    /** Tell the chosen approver a draft is waiting for them. */
    private function notifyApprover(LeadNote $draft, string $label): void
    {
        if (! $draft->approver_id || (int) $draft->approver_id === (int) Auth::id()) {
            return;
        }
        $company = Str::limit($draft->lead->company_name ?? '', 30);
        $msg = '✉️ '.Auth::user()->name.' sent you a '.strtolower($label).' for '.$company.' to approve.';
        SendPushNotification($draft->approver_id, $msg, 'lead');
        StoreLeadNotification($draft->lead_id, 'Draft Approval', $msg, $draft->approver_id, 'lead');
    }

    // public function update(Request $request, LeadNote $leadNote)
    // {
    //      $rules = [
    //         'lead_id'=>'required',
    //         'note'=>'required',
            
    //     ];

    //     $request->validate($rules);
    //     $data = $request->all();
    //     $created_by = Auth::id(); 
    //     $lead_note = LeadNote::where(['id'=>$leadNote->id])->first();
    //     if($lead_note){
    //          $lead_note->update(['note'=>$request->note,'lead_id'=>$request->lead_id,'created_by'=>$created_by]);
    //          $request->session()->flash('message_success',__('Lead Note Added successfully.'));
    //     }else{
    //          $request->session()->flash('message_info',__('something went wrong.'));

    //     }
       
    //     return redirect()->back();
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, LeadNote $leadNote)
    {
        $type = $leadNote->type;
        if ($leadNote->isApproved() && ! $leadNote->canBeApprovedBy(Auth::user())) {
            return redirect()->back()->with('lead_tab', ['email' => 'email-drafts', 'linkedin' => 'linkedin-drafts'][$type] ?? 'notes')
                ->withErrors(['note' => 'This draft is approved and can no longer be deleted.']);
        }
        $leadNote->delete();
        $label = ['email' => 'Email draft', 'linkedin' => 'LinkedIn draft'][$type] ?? 'Lead Note';
        $request->session()->flash('message_success', $label.' deleted successfully.');
        return redirect()->back()->with('lead_tab', ['email' => 'email-drafts', 'linkedin' => 'linkedin-drafts'][$type] ?? 'notes');
    }
}
