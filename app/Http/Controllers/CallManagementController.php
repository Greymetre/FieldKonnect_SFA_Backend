<?php

namespace App\Http\Controllers;

use App\Models\CallManagementEntry;
use App\Models\Pincode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CallManagementController extends Controller
{
    public function index()
    {
        abort_if(
            Gate::denies('call_management_access'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        $entries = CallManagementEntry::with('assignedUser:id,name')
            ->latest('id')
            ->get();

        $pincodes = Pincode::with([
                'cityname:id,city_name,district_id,state_id',
                'cityname.districtname:id,district_name,state_id',
                'cityname.districtname.statename:id,state_name',
                'cityname.statename:id,state_name',
            ])
            ->where('active', 'Y')
            ->orderBy('pincode')
            ->get();

        $callers = User::permission('call_management_access')
            ->where('active', 'Y')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('calls.index', compact('entries', 'pincodes', 'callers'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callerIds = User::permission('call_management_access')
            ->where('active', 'Y')
            ->pluck('id');

        $validated = $request->validate([
            'firm_name' => ['required', 'string', 'max:200'],
            'contact_person_name' => ['required', 'string', 'max:200'],
            'mobile_number' => ['required', 'regex:/^[0-9+ ]{10,15}$/'],
            'customer_type' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'pincode_id' => ['required', 'integer', 'exists:pincodes,id'],
            'assigned_user_id' => ['required', 'integer', Rule::in($callerIds->all())],
            'custom_column_1' => ['nullable', 'string', 'max:255'],
            'custom_column_2' => ['nullable', 'string', 'max:255'],
            'custom_column_3' => ['nullable', 'string', 'max:255'],
            'custom_column_4' => ['nullable', 'string', 'max:255'],
        ]);

        $pincode = Pincode::with(['cityname.districtname.statename', 'cityname.statename'])
            ->findOrFail($validated['pincode_id']);

        $city = $pincode->cityname;
        $district = optional($city)->districtname;
        $state = optional($district)->statename ?: optional($city)->statename;

        CallManagementEntry::create(array_merge($validated, [
            'pincode' => $pincode->pincode,
            'city' => optional($city)->city_name,
            'district' => optional($district)->district_name,
            'state' => optional($state)->state_name,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('calls.index')->with('message_success', 'Call entry added successfully.');
    }
}
