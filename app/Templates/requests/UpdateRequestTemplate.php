<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
//Module = PostCatalogue
//tableName = post_catalogue
class Update{Module}Request extends FormRequest
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
            'canonical' => 'required|unique:routes,canonical,'.$this->route('{tableName}').',module_id',
            'follow' => 'required|integer|gt:0',
            'status' => 'required|integer|gt:0',
            'meta_title' => 'required',
            'meta_keyword' => 'required',
            'meta_description' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên nhóm bài viết không được để trống.',
            'meta_title.required' => 'Tiêu đề SEO không được để trống.',
            'meta_keyword.required' => 'Từ khóa SEO không được để trống.',
            'meta_description.required' => 'Mô tả SEO không được để trống.',
            'follow.gt' => 'Bạn chưa chọn điều hướng',
            'status.gt' => 'Bạn chưa chọn tình trạng',
            'canonical.required' => 'Đường dẫn không được để trống.',
            'canonical.unique' => 'Đường dẫn đã tồn tại.',
        ];
    }
}
