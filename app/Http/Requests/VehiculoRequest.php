<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehiculoRequest extends FormRequest
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
			'placa' => ['required', 'regex:/^\d{3,4}[A-Z]{3}$/'],
			'capacidad_aproximada' => 'string',
			'modelo_anio' => ['nullable','integer','min:1975'],
			'modelo' => 'string',
            'marca' => 'string',
            'id_tipovehiculo' => ['required', 'integer'],
            'id_marca' => ['nullable','integer','exists:marca,id_marca'],
            'color'=>['nullable', 'string']
        ];
    }
}
