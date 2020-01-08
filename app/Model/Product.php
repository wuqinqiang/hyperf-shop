<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $description
 * @property int $on_sale
 * @property int $sold_count
 * @property int $review_count
 * @property float $price
 * @property int $sort
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $time_on
 * @property string $time_off
 * @property string $deleted_at
 * @property int $fare_type
 * @property float $fare
 * @property int $template_id
 * @property string $front_image
 * @property string $only_buy
 * @property string $only_count
 */
class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    const UN_SALE = 0;
    const ON_SALE = 1;
    const TIME_SALE = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'category_id', 'name', 'description', 'on_sale',
        'sold_count', 'review_count', 'price', 'sort', 'time_on',
        'time_off', 'fare', 'front_image', 'images','only_buy','only_count','category_name'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'category_id' => 'integer',
        'on_sale' => 'integer', 'sold_count' => 'integer',
        'review_count' => 'integer', 'price' => 'float',
        'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime',
        'fare' => 'float',  'images' => 'array','only_buy'=>'integer','only_count'=>'integer'];

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class);
    }

    public function skus()
    {
        return $this->hasMany(ProductSkus::class);
    }
}