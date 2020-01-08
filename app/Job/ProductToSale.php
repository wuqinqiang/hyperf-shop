<?php

declare(strict_types=1);

namespace App\Job;

use App\Model\Product;
use Hyperf\AsyncQueue\Job;

class ProductToSale extends Job
{
    public $product_id;

    public $type;

    public function __construct(int $product_id, int $type)
    {
        $this->product_id = $product_id;
        $this->type = $type;
    }


    public function handle()
    {
        $product = Product::find($this->product_id);
        if ($this->type == Product::ON_SALE) {
            $product->update(['on_sale' => Product::ON_SALE]);
        } else {
            $product->update(['on_sale' => Product::UN_SALE]);
        }
    }

}