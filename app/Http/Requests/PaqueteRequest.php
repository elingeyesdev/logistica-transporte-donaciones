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
            'imagen'         => ['required','string','max:255'],
            'ubicacion_actual' => ['nullable','string'],    
            'latitud'        => ['nullable','numeric'],
            'longitud'       => ['nullable','numeric'],
            'zona'           => ['nullable','string','max:255'],
        ];
    }
       public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $hasLat = $this->filled('latitud');
            $hasLng = $this->filled('longitud');
            if ($hasLat xor $hasLng) {
                $v->errors()->add('latitud',  'Si envías coordenadas, deben incluir latitud y longitud.');
                $v->errors()->add('longitud', 'Si envías coordenadas, deben incluir latitud y longitud.');
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
            'imagen'      => $this->filled('imagen')      ? trim($this->imagen)      : null,
            'zona'        => $this->filled('zona')        ? trim($this->zona)        : null,
        ]);
    }

}
