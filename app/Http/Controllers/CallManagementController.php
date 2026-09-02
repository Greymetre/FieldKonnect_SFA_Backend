<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CallManagementController extends Controller
{
    public function index()
    {
        abort_if(
            Gate::denies('call_management_access'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        return view('calls.index');
    }
}
