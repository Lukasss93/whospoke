<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkMemberToUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'int', 'exists:users,id'],
        ];
    }
}
