<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoomRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|alpha_dash:ascii|min:3|max:64|unique:rooms,code',
            'members' => 'array|min:2|max:20',
            'members.*' => 'min:1|max:32|string',
        ];
    }

    public function attributes()
    {
        return [
            'code' => sprintf('"%s"', trans('app.room.code.placeholder')),
            'members' => sprintf('"%s"', trans('app.room.members.title')),
            'members.*' => sprintf('"%s"', trans('app.room.members.placeholder')),
        ];
    }
}
