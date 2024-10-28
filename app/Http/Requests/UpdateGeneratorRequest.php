<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneratorRequest extends FormRequest
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
            'name' => 'required|unique:generators,name,' . $this->route('generator') . '',
            'schema' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên module không được để trống.',
            'name.unique' => 'Tên module đã tồn tại.',
            'schema.required' => 'Schema không được để trống.',
        ];
    }
}
