<?php

namespace App\Http\Requests\Room;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RoomFormRequest extends FormRequest
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
            'number'        =>  'required|integer',
            'price'         =>  'required|integer',
            'description'   =>  'required|min:5|max:500'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, response()->json([
            'status'    =>  'error',
            'errors'    =>  $validator->errors(),
            'msg'       =>  'Неверные данные'
        ])));
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('Вы не авторизованы', 403);
    }
}
