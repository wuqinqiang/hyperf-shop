<?php

namespace App\Exception\Handler;

use App\Exception\BusinessException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class BusinessExceptionHandle extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $data = json_encode([
            'code' => $throwable->getCode(),
            'message' => $throwable->getMessage(),
        ],JSON_UNESCAPED_UNICODE);
        $this->stopPropagation();
        return $response->withStatus(400)->withBody(new SwooleStream($data));

    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof BusinessException || $throwable instanceof \InvalidArgumentException;
    }
}