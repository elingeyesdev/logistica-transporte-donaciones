<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReporteRequest extends FormRequest
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
			'direccion_archivo' => ['nullable', 'string', 'max:255'],
            'fecha_reporte'     => ['required', 'date'],
            'gestion'           => ['nullable', 'string', 'max:255'],
            'id_paquete'        => ['required', 'integer', 'exists:paquete,id_paquete'],
        ];
    }
}
