<?php

declare(strict_types=1);

namespace App\Request\Admin;

use App\Model\Product;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class ProductRequest extends FormRequest
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
            $product = $this->getUri()->getPath();
            preg_match('/\d+/', $product, $arr);
            $product = Product::find(intval($arr[0]));
            return [
                'category_id' => [
                    'required',
                    'integer',
                    Rule::exists('category', 'id'),
                ],
                'name' => "max:50|unique:products,name," . $product->id,
                'description' => 'required',
                'sort' => 'required',
                'image_address' => 'required|array',
                'on_sale' => 'integer|between:0,3',
                'front_image' => 'required',
                'skus' => 'array',
                'skus.*.title' => 'required|max:50',
                'skus.*.before_price' => 'required|numeric',
                'skus.*.price' => 'required|numeric',
                'skus.*.stock' => 'required|integer|max:9999',
                'skus.*.sku_image' => 'required',
                'time_on' => 'required_if:on_sale,2',
                'time_off' => 'required_if:on_sale,2',
                'only_buy' => 'integer|max:9999',
                'only_count' => 'integer|max:9999',

            ];
        } else {
            return [
                'name' => 'required|max:50|unique:products',
                'category_id' => [
                    'required',
                    'integer',
                    Rule::exists('category', 'id'),
                ],
                'description' => 'required|max:15',
                'sort' => 'max:99999',
                'image_address' => 'required|array',
                'on_sale' => 'required|integer|between:0,3',
                'front_image' => 'required|max:199',
                'skus' => 'array',
                'skus.*.title' => 'required|max:50',
                'skus.*.before_price' => 'required|numeric|min:0.01',
                'skus.*.price' => 'required|numeric',
                'skus.*.stock' => 'required|integer|max:9999',
                'skus.*.sku_image' => 'required',
                'time_on' => 'required_if:on_sale,2',
                'time_off' => 'required_if:on_sale,2',
                'fare' => 'required_if:fare_type,1|numeric|min:0',
                'only_buy' => 'max:9999',
                'only_count' => 'max:9999'
            ];
        }
    }

    public function attributes(): array
    {
        return [
            'name' => '商品名称',
            'category_id' => '分类',
            'sort' => '排序',
            'image_address' => '商品轮播图片',
            'description' => '商品标题',
            'on_sale' => '是否上架',
            'skus' => '商品规格',
            'descriptions' => '商品简介',
            'skus.*.title' => '规格名称',
            'skus.*.before_price' => '原价',
            'skus.*.price' => '现价',
            'skus.*.stock' => '库存',
            'skus.*.sku_image' => '子商品图片',
            'time_on' => '上架时间',
            'time_off' => '下架时间',
            'fare_type' => '快递运费类型',
            'fare' => '运费',
            'template_id' => '运费模板',
            'ratios' => '商品折扣规则',
            'front_image' => '商品封面图',
            'skus.*.ratios.*.user_type' => '用户类型',
            'skus.*.ratios.*.discount' => '用户折扣',
            'skus.*.ratios.*.return_commission' => '用户佣金',
        ];
    }


}
