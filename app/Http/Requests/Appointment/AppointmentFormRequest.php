<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AppointmentFormRequest extends FormRequest
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
            'appointment' => 'required|max:255'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, response()->json([
            'errors'    => $validator->errors(),
            'msg'       => 'Неверные данные',
            'status'    => 'error'
        ])));
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('Вы не авторизованы.', 0);
    }
}
