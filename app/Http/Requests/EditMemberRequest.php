<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditMemberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'int', 'exists:users,id'],
            'profession_id' => ['nullable', 'int', 'exists:professions,id'],
        ];
    }
}
