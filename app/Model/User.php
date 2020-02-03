<?php

declare (strict_types=1);

namespace App\Model;

use Donjan\Permission\Traits\HasRoles;
use Hyperf\DbConnection\Model\Model;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 */
class User extends Model
{
    use HasRoles;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'email', 'phone', 'gender', 'type', 'password', 'created_at', 'updated_at',
        'deleted_at', 'monetary', 'open_id', 'wechat_openid', 'union_id', 'avatar_url', 'wechat', 'location',
        'buy_count', 'last_buy','status'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    protected $hidden=['password'];


    /**
     * @param  RequestInterface $request
     */
    public static function checkUser($request)
    {
        $userInfo = $request->all();
        if (!$user = User::query()->where('name', $userInfo['name'])
            ->orWhere('email', $userInfo['name'])->first()) {
            return false;
        }
        if (false === password_verify($userInfo['password'], $user->password)) {
            return false;
        }
        //其他条件暂时没有
        return true;
    }
}