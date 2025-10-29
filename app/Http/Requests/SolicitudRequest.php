<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'carnet_identidad' => 'required|string|max:50',
            'correo_electronico' => 'nullable|email|max:255',
            'comunidad_solicitante' => 'nullable|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'nro_celular' => 'nullable|string|max:50',
            'cantidad_personas' => 'nullable|integer|min:1',
            'fecha_inicio' => 'nullable|date',
            'tipo_emergencia' => 'required|string|max:255',
            'insumos_necesarios' => 'nullable|string',
            'codigo_seguimiento' => 'nullable|string|max:255',
        ];
    }
}
