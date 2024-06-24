<?php

namespace App\Http\Requests;

use App\Enums\MemberType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMemberTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(MemberType::class)],
        ];
    }
}
