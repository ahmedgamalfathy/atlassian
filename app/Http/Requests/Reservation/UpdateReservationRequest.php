<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateReservationRequest extends FormRequest
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
    {
        return [
            "title"=>['nullable'],
            "dateTo"=>["nullable"],
            "reservationId"=>['required','exists:reservations,id'],
            "clientId"=>['required','exists:clients,id'],
            "serviceId"=>["required",'exists:services,id'],
            "notes"=>["nullable","string"],
            "date"=>["required"],
            "clientPhonesId"=>["required","array"],
            "clientEmailsId"=>["required","array"]
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }
}
