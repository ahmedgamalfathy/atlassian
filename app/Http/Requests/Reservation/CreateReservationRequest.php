<?php

namespace App\Http\Requests\Reservation;

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
    {//client_id ,service_id, date,notes
        return [
            "title"=>['nullable'],
           "clientId"=>['required','exists:clients,id'],
           "serviceId"=>["required",'exists:services,id'],
           "notes"=>["nullable","string"],
           "date"=>["required"],
           "dateTo"=>["nullable"],
           "clientPhonesId"=>["required","array"],
           "clientEmailsId"=>["nullable","array"]
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }
}
