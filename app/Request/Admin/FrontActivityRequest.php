<?php

declare(strict_types=1);

namespace App\Request\Admin;

use App\Model\FrontActivity;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class FrontActivityRequest extends FormRequest
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
            $activity = $this->getUri()->getPath();
            preg_match('/\d+/', $activity, $arr);
            $activity = FrontActivity::find(intval($arr[0]));
            return [
                'type' => [
                    'integer',
                    Rule::exists('front_category', 'id'),
                ],
                'name' => "max:50|unique:front_activities,name," . $activity->id,

                'product_id' => [
                    'integer',
                    Rule::exists('products', 'id'),
                ],
                'status' => 'integer|between:0,1',
            ];
        } else {
            return [
                'name' => 'required|max:50|unique:front_activities',
                'type' => [
                    'required',
                    'integer',
                    Rule::exists('front_category', 'id'),
                ],
                'product_id' => [
                    'required',
                    'integer',
                    Rule::exists('products', 'id'),
                ],

                'start_at' => 'required|date',
                'end_at' => 'required|date',
                'image' => 'required',
                'status' => 'integer|between:0,1',
            ];
        }
    }
}
