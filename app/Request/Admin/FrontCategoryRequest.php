<?php

declare(strict_types=1);

namespace App\Request\Admin;

use App\Model\FrontCategory;
use Hyperf\Validation\Request\FormRequest;

class FrontCategoryRequest extends FormRequest
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
     */
    public function rules(): array
    {
        if ($this->getMethod() == 'PATCH') {
            $category = $this->getUri()->getPath();
            pregw_match('/\d+/', $category, $arr);
            $frontCategory = FrontCategory::find(intval($arr[0]));
            return [
                'name' => "max:50|unique:front_category,name," . $frontCategory->id,
            ];
        } else {
            return [
                'name' => 'required|max:50|unique:front_category',
                'detail' => 'required|max:50'
            ];
        }
    }

    public function attributes():array
    {
        return [
            'name' => '类目名称',
            'detail' => '类目的描述',
        ];
    }
}
