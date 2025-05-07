<?php

namespace App\Http\Requests\Reservation\website;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateReservationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {//name ,email ,phones
        return [
           "title"=>['nullable'],
           'name'=>['required','string'],
           "email"=>['nullable','email'],
           "phone"=>["required"],
           "serviceId"=>["required",'exists:services,id'],
           "date"=>["required"],
           "dateTo"=>["nullable"],
           "notes"=>["nullable","string"],
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }
}
