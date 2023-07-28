<?php

namespace App\Modules\User\Domain\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use \LaravelLegends\PtBrValidator\Rules\Cpf;
use \LaravelLegends\PtBrValidator\Rules\FormatoCep;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string'],

            "people.name" => ['required'],
            "people.cpf" => ['required', 'string', new Cpf],
            "people.rg" => ['required'],
            "people.birthday" => ['required'],

            "people.address.*.street" => ['required', 'string'],
            "people.address.*.number" => ['required', 'string'],
            "people.address.*.neighborhoods" => ['required', 'string'],
            "people.address.*.country" => ['required', 'string'],
            "people.address.*.state" => ['required', 'string'],
            "people.address.*.cep" => ['required', 'string', new FormatoCep],
        ];
    }
}
