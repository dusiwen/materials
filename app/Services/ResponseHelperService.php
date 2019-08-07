<?php

namespace app\Services;

use Illuminate\Support\Facades\Response;

class ResponseHelperService
{
    public function read($data)
    {
        return Response::json($data, 200);
    }

    public function success(string $message = '操作成功', int $status = 200)
    {
        return Response::make($message, $status);
    }

    public function fail(string $message = '意外错误', int $status = 500)
    {
        return Response::make($message, $status);
    }

    public function null(string $message = '数据不存在', int $status = 404)
    {
        return Response::make($message, $status);
    }

    public function repeat(string $message = '数据重复', int $status = 404)
    {
        return Response::make($message, $status);
    }

    public function validate(string $message, int $status = 422)
    {
        return Response::make($message, $status);
    }
}
