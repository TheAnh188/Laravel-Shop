<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserCatalogueRequest extends FormRequest
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
            'name' => 'required|unique:user_catalogues,name,' . $this->route('user_catalogue') . '|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên nhóm không được để trống.',
            'name.unique' => 'Tên nhóm đã tồn tại.',
            'name.string' => 'Tên nhóm phải là dạng kí tự.',
        ];
    }
}
