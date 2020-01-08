<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $image 
 * @property string $url 
 * @property int $status 
 * @property int $weight 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class FrontImage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'front_image';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','image','url','status','weight'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'status' => 'integer', 'weight' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}