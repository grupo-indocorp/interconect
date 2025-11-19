<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
        $rules = [
            'dni_cliente' => 'required|numeric|digits:8|unique:clientes,dni_cliente',
            'nombre_cliente' => 'required|string',
            'apellido_paterno_cliente' => 'required|string',
            'etapa_id' => 'required|bail',
            'comentario' => 'required|bail',
        ];
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'dni_cliente.required' => 'El "DNI" es obligatorio.',
            'dni_cliente.numeric' => 'El "DNI" debe ser numérico.',
            'dni_cliente.digits' => 'El "DNI" debe tener exactamente 8 dígitos.',
            'dni_cliente.unique' => 'El "DNI" ya se encuentra registrado.',
            'nombre_cliente.required' => 'El "Nombre" es obligatorio.',
            'apellido_paterno_cliente.required' => 'El "Apellido Paterno" es obligatorio.',

            'etapa_id.required' => 'La "Etapa" es obligatorio.',
            'comentario.required' => 'El "Comentario" es obligatorio.',
        ];
    }
}
