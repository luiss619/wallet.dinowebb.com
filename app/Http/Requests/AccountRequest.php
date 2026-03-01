<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'bank'           => ['required', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'balance'        => ['required', 'numeric'],
            'currency'       => ['required', 'string', 'size:3'],
            'status'         => ['required', 'in:0,1'],
        ];
    }
}
