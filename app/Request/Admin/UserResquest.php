<?php

declare(strict_types=1);

namespace App\Request\Admin;

use Hyperf\Validation\Request\FormRequest;

class UserResquest extends FormRequest
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
        return [
            'name' => 'required|max:10',
            'password' => 'required|min:6',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '姓名',
            'password' => '密码',
        ];
    }
}
