<?php

declare(strict_types=1);

namespace App\Request\Admin;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;
use SebastianBergmann\CodeCoverage\Report\PHP;

class CategoryRequest extends FormRequest
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
        $method = $this->getRequest()->getMethod();
        if ($method === 'POST') {
            return [
                'name' => 'required|unique:category|max:20',
                'pid' => 'integer',
                'sort' => 'integer',
            ];
        } else if ($method === 'PATCH') {
            return [
                'id' => [
                    'required',
                    Rule::exists('category', 'id'),
                ],
                'name' => 'required|unique:category|max:20',
                'pid' => 'integer',
                'sort' => 'integer',
            ];
        }

    }

    public function attributes(): array
    {
        return [
            'name' => '分类名称',
            'pid' => '父级分类',
            'sort' => '权重',
        ];
    }
}
