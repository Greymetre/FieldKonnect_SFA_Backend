<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Illuminate\Validation\Rule;

class StatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('status_create') || Gate::denies('status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;

    }

    public function rules()
    {
        $max = $this->filled('id') ? 100 : 200;

        return [
            'status_name' => [
                'required', 'string', 'min:2', 'max:'.$max, 'regex:/[a-zA-Z0-9\s]+/',
                Rule::unique('statuses', 'status_name')
                    ->where(fn ($query) => $query->where('module', $this->input('module')))
                    ->ignore($this->input('id')),
            ],
            'display_name' => ['required', 'string', 'min:2', 'max:'.$max, 'regex:/[a-zA-Z0-9\s]+/'],
            'status_message' => ['required', 'string', 'min:2', 'max:'.$max, 'regex:/[a-zA-Z0-9\s]+/'],
            'module' => ['required', 'string', 'min:2', 'max:'.$max, 'regex:/[a-zA-Z0-9\s]+/'],
        ];
    }

    public function messages()
    {
        return [
            'status_name.unique' => 'This status name already exists in the selected module.',
        ];
    }
}
