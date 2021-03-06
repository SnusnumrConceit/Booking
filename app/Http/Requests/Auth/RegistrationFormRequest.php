<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegistrationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'         => 'required|email|min:10|max:255',
            'password'      => 'required|min:8|max:50',
            'last_name'     => 'required|min:2|max:50',
            'first_name'    => 'required|min:4|max:25',
            'middle_name'   => 'required|min:4|max:40',
            'birthday'      => 'required|date'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw(new ValidationException($validator, response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
            'msg' => 'Неверные данные'
        ])));
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('Вы не авторизованы', 403);
    }
}
