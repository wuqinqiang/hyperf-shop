<?php

declare(strict_types=1);

namespace App\Middleware\Admin;

use App\Exception\BusinessException;
use App\Model\Product;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProductMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        if ($data['on_sale'] == Product::TIME_SALE) {
            $time_on = strtotime($data['time_on']);
            $time_off = strtotime($data['time_off']);
        }
        if (count($data['image_address']) < 2) {
            throw new BusinessException('上传图片不能小于2张', 400);

        }
        if ($data['on_sale'] == Product::TIME_SALE && $time_on >= $time_off) {
            throw new BusinessException('上架时间不能大于下架时间', 400);
        }

        if ($data['on_sale'] == Product::TIME_SALE && $time_on <= time()) {
            throw new BusinessException('上架时间要大于当前时间', 400);
        }
        return $handler->handle($request);
    }
}