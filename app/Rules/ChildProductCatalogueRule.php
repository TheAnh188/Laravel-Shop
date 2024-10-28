<?php

namespace App\Rules;

use App\Models\ProductCatalogue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChildProductCatalogueRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $flag = ProductCatalogue::isChildNode($this->id);
        if(!$flag) {
            $fail('Không thể xóa do vẫn còn danh mục con');
        }else {

        }
    }
}
