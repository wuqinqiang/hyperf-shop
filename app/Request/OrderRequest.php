<?php

declare(strict_types=1);

namespace App\Request;

use App\Exception\BusinessException;
use App\Model\Product;
use App\Model\ProductSkus;
use http\Exception\InvalidArgumentException;
use Hyperf\Validation\Request\FormRequest;

class OrderRequest extends FormRequest
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
            'address' => 'required',
            'user_name' => 'required',
            'user_phone' => 'required',
            'items' => 'required|array',
            'items.*.amount' => 'required|integer|min:1',
            'items.*.sku_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSkus::find($value)) {
                        throw new BusinessException('商品不存在');
                    }
                    if ($sku->product->on_sale !== Product::ON_SALE) {
                        throw new BusinessException('商品未上架');
                    }
                    if ($sku->stock == 0) {
                        throw new BusinessException('商品卖完了');
                    }
                    preg_match('/items\.(\d+)\.sku_id/', $attribute, $m);
                    $index = $m[1]; //当前是哪个sku_Id
                    $amount = $this->input('items')[$index]['amount'];
                    if ($amount > 0 && $amount > $sku->stock) {
                        throw new BusinessException('商品库存不足');
                    }
                }
            ],
        ];
    }
}
