<?php

namespace App\Http\Requests\Order;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class OrderFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer'  =>  'required|integer',
            'room'      =>  'required|integer',
            'status'    =>  'required|integer',
            'note_date' =>  'required|date',
            'note_time' =>  'required',
            'days'      =>  'required|integer',
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
        throw new AuthorizationException('Вы не авторизованы.', 403);
    }
}
