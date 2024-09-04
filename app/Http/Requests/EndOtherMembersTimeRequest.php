<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EndOtherMembersTimeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'except' => 'array',
            'except.*' => 'integer',
        ];
    }
}
