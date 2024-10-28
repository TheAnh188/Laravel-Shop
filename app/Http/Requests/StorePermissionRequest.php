<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'canonical' => 'required|unique:permissions',
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
