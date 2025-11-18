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
        // PERSONA
        'nombre'             => ['required','string','max:255'],
        'apellido'           => ['required','string','max:255'],
        'carnet_identidad'   => ['required','string','max:255'],
        'correo_electronico' => ['required','email','max:255'],
        'nro_celular'        => ['required','string','max:50'],

        // DESTINO
        'comunidad_solicitante' => ['required','string','max:255'],
        'provincia'             => ['required','string','max:255'],
        'ubicacion'             => ['required','string','max:255'],
        'latitud'               => ['required','numeric'],
        'longitud'              => ['required','numeric'],

        // SOLICITUD 
        'cantidad_personas'  => ['required','integer','min:0'],
        'fecha_inicio'       => ['required','date'],
        'insumos_necesarios' => ['required','string'],
        'codigo_seguimiento' => ['required','string','max:255'],
        'estado'             => ['nullable','string','max:255'],
        'fecha_solicitud'    => ['nullable','date'],
        'aprobada'           => ['nullable','boolean'], 
        'apoyoaceptado'      => ['nullable','boolean'],
        'justificacion'      => ['nullable','string','max:255'],

        'id_tipoemergencia'  => ['required','integer','exists:tipo_emergencia,id_emergencia'],
    ];
}

}
