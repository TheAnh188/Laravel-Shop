<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class TranslateRequest extends FormRequest
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
            'translated_name' => 'required',
            'translated_canonical' => [
                'required',
                function ($attribute, $value, $fail) {
                    $option = $this->input('option');
                    $exist = DB::table('routes')
                        ->where('canonical', '=', $value)
                        ->where(function ($query) use ($option) {
                            $query->where('controller', '<>', "App\\Http\\Controllers\\Frontend\\".$option['model_name'].'Controller')
                                ->orWhere('module_id', '<>', $option['id'])
                                ->orWhere('language_id', '<>', $option['language_id']);
                        })
                        ->exists();
                    if ($exist) {
                        $fail('Đường dẫn đã tồn tại.');
                    }
                }
            ],
            'translated_meta_title' => 'required',
            'translated_meta_keyword' => 'required',
            'translated_meta_description' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'translated_name.required' => 'Tên nhóm bài viết không được để trống.',
            'translated_canonical.required' => 'Đường dẫn không được để trống.',
            'translated_canonical.unique' => 'Đường dẫn đã tồn tại.',
            'translated_meta_title.required' => 'Tiêu đề SEO không được để trống.',
            'translated_meta_keyword.required' => 'Từ khóa SEO không được để trống.',
            'translated_meta_description.required' => 'Mô tả SEO không được để trống.',
        ];
    }
}
