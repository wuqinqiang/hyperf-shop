<?php

namespace App\Exception\Handler;

use App\Exception\BusinessException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Phper666\JwtAuth\Exception\TokenValidException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AuthExceptionHandle extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $data = json_encode([
            'code' => 401,
            'message' => '身份验证失败',
        ],JSON_UNESCAPED_UNICODE);
        $this->stopPropagation();

        return $response->withStatus(401)->withBody(new SwooleStream($data));

    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof TokenValidException;
    }
}