<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginFormRequest extends FormRequest
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
            'email'     =>  'required|email|min:10|max:100',
            'password'  =>  'required|min:8|max:50'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, response()->json([
            'status' => 'error',
            'msg'   =>  'Неверные данные'
        ])));
    }
}
