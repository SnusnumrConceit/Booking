<?php

namespace App\Http\Requests\Employee;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EmployeeFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole('superadmin|admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'last_name'      => 'required|min:2|max:50',
            'first_name'     => 'required|min:4|max:25',
            'middle_name'    => 'required|min:4|max:40',
            'birthday'       => 'required|date',
            'appointment_id' => 'required|integer'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw(new ValidationException($validator, response()->json([
            'errors'    => $validator->errors(),
            'msg'       => 'Неверные данные',
            'status'    => 'error'
        ])));
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('Вы не авторизованы', 403);
    }
}
