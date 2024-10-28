<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
            'name' => 'required',
            'canonical' => 'required|unique:permissions,canonical,' . $this->route('permission') . '',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên quyền không được để trống.',
            'canonical.required' => 'Từ khóa không được để trống.',
            'canonical.unique' => 'Từ khóa đã tồn tại.',
        ];
    }
}
