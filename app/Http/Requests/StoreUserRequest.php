<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|email|string|unique:users|max:191',
            'name' => 'required|string',
            'user_catalogue_id' => 'required|integer|gt:0',
            'password' => 'required|string|min:6',
            'repeat_password' => 'same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Nhập đúng định dạng email.',
            'email.unique' => 'Email đã được đăng kí.',
            'email.string' => 'Email phải là dạng kí tự.',
            'email.max' => 'Độ dài tối đa của email là 191 kí tự.',
            'name.required' => 'Tên không được để trống.',
            'name.string' => 'Tên phải là dạng kí tự.',
            'user_catalogue_id.gt' => 'Bạn chưa chọn nhóm thành viên',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu chứa ít nhất 6 kí tự.',
            'password.string' => 'Mật khẩu phải là dạng kí tự.',
            'repeat_password.same' => 'Mật khẩu không khớp.',
        ];
    }
}
