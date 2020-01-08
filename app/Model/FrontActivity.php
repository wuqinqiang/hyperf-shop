<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $type
 * @property string $start_at
 * @property string $end_at
 * @property string $url
 * @property string $image
 * @property int $sort
 * @property int $status
 * @property int $click
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class FrontActivity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'front_activities';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'name', 'type', 'start_at',
        'end_at', 'url', 'image', 'sort', 'status', 'click'];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'start_at' => 'datetime', 'end_at' => 'datetime', 'product_id' => 'integer', 'sort' => 'integer', 'status' => 'integer', 'click' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime','type'=>'integer'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}