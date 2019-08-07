<?php

namespace App\Services;

use App\Model\TestLog;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TestLogService
{
    use Helpers;

    public function write($content)
    {
        try {
            if ((is_array($content) || is_object($content))) $content = json_encode($content, 256);
            $testLog = new TestLog;
            $testLog->content = $content;
            $testLog->saveOrFail();
            return true;
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
