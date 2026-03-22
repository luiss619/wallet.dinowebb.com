<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'type'       => ['required', 'in:0,1,2'],
            'quantity'   => ['required', 'numeric', 'not_in:0'],
            'date'           => ['required', 'date'],
            'description'    => ['nullable', 'string', 'max:500'],
            'status'         => ['required', 'in:0,1'],
        ];
    }
}
