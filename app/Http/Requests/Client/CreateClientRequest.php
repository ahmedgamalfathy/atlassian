<?php

namespace App\Http\Requests\Client;

use App\Enums\Client\AddableToBulk;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class CreateClientRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            "name" => ['required','string'],
            'phones' => ['required','array'],
            'phones.*.phone'=>['required','unique:phones,phone'],
            'emails' => ['required','array'],
            'emails.*.email'=>['required','unique:emails,email'],
            'addresses' => ['required','array'],
            'addresses.*.address'=>['required','unique:addresses,title'],
            "description" => ['nullable','string'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}
