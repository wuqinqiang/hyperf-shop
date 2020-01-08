<?php

namespace App\Controller\Admin;

use App\Controller\CommonController;
use App\Model\FrontActivity;
use App\Request\Admin\FrontActivityRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class FrontActivityController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class FrontActivityController extends CommonController
{

    public function getModel()
    {
        return 'App\Model\FrontActivity';
    }

    public function mergeQuery($query, $request)
    {
        return $query->with('product')
            ->when($type = $request->input('type'), function ($query) use ($type) {
                $query->whereType(intval($type));
            })
            ->when($start_time = $request->input('start_time'), function ($query) use ($start_time) {
                $query->where('start_at', '>=', $start_time);
            })
            ->when($end_time = $request->input('end_time'), function ($query) use ($end_time) {
                $query->where('end_at', '<=', $end_time);
            })
            ->when($name = $request->input('name'), function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
    }

    public function isCanDelete(object $model)
    {
        return true;
    }

    /**
     * @PostMapping(path="")
     */
    public function store(FrontActivityRequest $request)
    {
        $activity = FrontActivity::create($request->all());
        return $this->response->json($activity);
    }

    /**
     * @PatchMapping(path="{id:\d+}")
     */
    public function update(FrontActivityRequest $request, int $id)
    {
        $activity = FrontActivity::find($id);
        $activity->fill($request->all());
        $activity->save();

        return $this->response->json($activity);
    }


}