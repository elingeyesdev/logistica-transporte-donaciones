<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistorialSeguimientoDonacioneRequest extends FormRequest
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
		'ci_usuario'        => ['nullable','string','max:255'],
        'estado'            => ['nullable','string','max:255'],
        'imagen_evidencia'  => ['nullable','string','max:255'],
        'fecha_actualizacion'=> ['nullable','date'],
        'id_paquete'        => ['nullable','integer','exists:paquete,id_paquete'],
        'id_ubicacion'      => ['nullable','integer','exists:ubicacion,id_ubicacion'],
        ];
    }
}
