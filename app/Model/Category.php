<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $name 
 * @property int $pid 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property int $sort 
 */
class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name','pid','created_at','updated_at','sort'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'pid' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'sort' => 'integer'];

    /**
     * @return array
     */
    public static function generateTree()
    {
        $data = self::all()->toArray();
        $items = [];
        foreach ($data as $value) {
            $items[$value['id']] = $value;
        }
        $tree = [];
        foreach ($items as $k => $v) {
            if (isset($items[$v['pid']])) {
                $items[$v['pid']]['children'][] = &$items[$k];
            } else {
                $tree[] = &$items[$k];
            }

        }
        return $tree;
    }
}