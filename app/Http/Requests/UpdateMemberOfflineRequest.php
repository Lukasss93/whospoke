<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberOfflineRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'offline' => 'required|boolean',
        ];
    }
}
