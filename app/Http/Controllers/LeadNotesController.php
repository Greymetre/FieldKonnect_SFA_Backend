<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $request->validate([
            'lead_id' => 'required',
            'note' => 'required',
            'type' => 'nullable|in:'.implode(',', LeadNote::TYPES),
        ]);
        $type = $request->input('type', LeadNote::TYPE_NOTE);
        $labels = [
            LeadNote::TYPE_NOTE => ['Lead Note', 'notes'],
            LeadNote::TYPE_EMAIL => ['Email draft', 'email-drafts'],
            LeadNote::TYPE_LINKEDIN => ['LinkedIn draft', 'linkedin-drafts'],
        ];
        [$label, $tab] = $labels[$type];

        $lead_note = $request->note_id ? LeadNote::where('id', $request->note_id)->first() : null;
        if ($lead_note && $lead_note->isApproved() && ! Auth::user()->can('draft_approval')) {
            return redirect()->back()->with('lead_tab', $labels[$lead_note->type][1] ?? $tab)
                ->withErrors(['note' => 'This draft is approved and can no longer be edited.']);
        }
        if ($lead_note) {
            // Editing keeps the entry's own type (a note stays a note).
            $lead_note->update(['note' => $request->note, 'lead_id' => $request->lead_id, 'created_by' => Auth::id()]);
            $request->session()->flash('message_success', $label.' updated successfully.');
            $tab = $labels[$lead_note->type][1] ?? $tab;
        } else {
            LeadNote::create(['type' => $type, 'note' => $request->note, 'lead_id' => $request->lead_id, 'created_by' => Auth::id()]);
            $request->session()->flash('message_success', $label.' added successfully.');
        }

        // Reopen the tab the entry was saved from.
        return redirect()->back()->with('lead_tab', $tab);
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
        if ($leadNote->isApproved() && ! Auth::user()->can('draft_approval')) {
            return redirect()->back()->with('lead_tab', ['email' => 'email-drafts', 'linkedin' => 'linkedin-drafts'][$type] ?? 'notes')
                ->withErrors(['note' => 'This draft is approved and can no longer be deleted.']);
        }
        $leadNote->delete();
        $label = ['email' => 'Email draft', 'linkedin' => 'LinkedIn draft'][$type] ?? 'Lead Note';
        $request->session()->flash('message_success', $label.' deleted successfully.');
        return redirect()->back()->with('lead_tab', ['email' => 'email-drafts', 'linkedin' => 'linkedin-drafts'][$type] ?? 'notes');
    }
}
