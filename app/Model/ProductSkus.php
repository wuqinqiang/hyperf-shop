<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $title 
 * @property int $product_id 
 * @property float $before_price 
 * @property float $price 
 * @property int $stock 
 * @property string $sku_image 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class ProductSkus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_skus';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'before_price', 'price', 'stock', 'product_id', 'sku_image'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'product_id' => 'integer', 'before_price' => 'float', 'price' => 'float', 'stock' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}