<?php

namespace App\Http\Requests;
use App\Rules\ChildProductCatalogueRule;
use Illuminate\Foundation\Http\FormRequest;
//Module = PostCatalogue
//tableName = post_catalogue
class DeleteProductCatalogueRequest extends FormRequest
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
            'name' => [
                'required',
                new ChildProductCatalogueRule($this->route('product_catalogue')),

            ]
        ];
    }
}
