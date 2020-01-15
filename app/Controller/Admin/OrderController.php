<?php
namespace App\Controller\Admin;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class OrderController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class OrderController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {

    }

    /**
     * @PostMapping(path="")
     */
    public function store()
    {

    }

    /**
     * @PostMapping(path="{id:\d+}")
     */
    public function goShip()
    {

    }

    /**
     * @PostMapping(path="handle/{id:\d+}")
     */
    public function handleRefund()
    {

    }
}