<?php

namespace App\Rules;

use App\Models\PostCatalogue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChildPostCatalogueRule implements ValidationRule
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
        $flag = PostCatalogue::isChildNode($this->id);
        if(!$flag) {
            $fail('Không thể xóa do vẫn còn danh mục con');
        }else {

        }
    }
}
