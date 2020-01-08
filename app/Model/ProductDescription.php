<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property int $type
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProductDescription extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_descriptions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'type', 'content'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'product_id' => 'integer', 'type' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
    {
        return $this->belongsTo(Product::class);
    }

    public static function createProductDescription($descriptions, $product_id)
    {
        foreach ($descriptions as $item) {
            ProductDescription::create([
                'product_id' => $product_id,
                'type' => $item['type'],
                'content' => $item['content'],
            ]);
        }
    }
}