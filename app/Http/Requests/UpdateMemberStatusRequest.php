<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|boolean',
        ];
    }
}
