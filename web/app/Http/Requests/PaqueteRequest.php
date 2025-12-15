<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class paqueteRequest extends FormRequest
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
            'id_solicitud'   => ['required','integer','exists:solicitud,id_solicitud'],
            'estado_id'      => ['required','integer','exists:estado,id_estado'],
            'imagen'         => ['nullable', 'image', 'max:4096'],
            'codigo'         => ['nullable', 'string'],
            'ubicacion_actual' => ['nullable','string'],    
            'latitud'        => ['nullable','numeric'],
            'longitud'       => ['nullable','numeric'],
            'zona'           => ['nullable','string','max:255'],
            'id_conductor'      => ['nullable','integer','exists:conductor,conductor_id'],
            'id_vehiculo'       => ['nullable','integer','exists:vehiculo,id_vehiculo'],
        ];
    }
       public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $hasLat = $this->filled('latitud');
            $hasLng = $this->filled('longitud');

            if ($hasLat xor $hasLng) {
                $v->errors()->add('latitud', 'Debes enviar latitud y longitud juntas.');
            }

        });
    }
        public function messages(): array
    {
        return [
            'id_solicitud.required' => 'Debes seleccionar la solicitud origen.',
            'estado_id.required'    => 'Selecciona un estado.',
            'latitud.numeric'       => 'Latitud debe ser numérica.',
            'longitud.numeric'      => 'Longitud debe ser numérica.',
            'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser anterior a la aprobación.',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'zona'        => $this->filled('zona')        ? trim($this->zona)        : null,
        ]);
    }

}
