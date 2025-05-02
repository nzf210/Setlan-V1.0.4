<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

define("MAX", "max:255");

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', MAX],
            'username' => ['required', 'string', 'lowercase', MAX, Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['required', 'string', 'lowercase', MAX, Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}
