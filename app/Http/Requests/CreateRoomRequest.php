<?php

namespace App\Http\Requests;

use App\Enums\RoomType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateRoomRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(RoomType::class)],
            'title' => 'nullable|string|max:255',
            'code' => 'required|string|alpha_dash:ascii|min:3|max:64|unique:rooms,code',
            'members' => 'array|min:2|max:20',
            'members.*' => 'min:1|max:32|string',
        ];
    }

    public function attributes()
    {
        return [
            'title' => sprintf('"%s"', trans('app.room.title.placeholder')),
            'code' => sprintf('"%s"', trans('app.room.code.placeholder')),
            'members' => sprintf('"%s"', trans('app.room.members.title')),
            'members.*' => sprintf('"%s"', trans('app.room.members.placeholder')),
        ];
    }
}
