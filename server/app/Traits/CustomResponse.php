<?php

namespace App\Traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait CustomResponse
{

    public object $res;

    public function __construct()
    {
        $this->res = new \stdClass();
    }

    /**
     * @param $msg
     * @param $statusCode
     * @return void
     */
    public function success($msg = null, $statusCode = Response::HTTP_OK): void
    {
        $this->res->success = true;
        $this->res->message = $msg;
        $this->res->statusCode = $statusCode;
    }

    /**
     * @param $msg
     * @param $statusCode
     * @return void
     */
    public function error($msg = null, $statusCode = Response::HTTP_BAD_REQUEST): void
    {
        $this->res->error = true;
        $this->res->message = $msg;
        $this->res->statusCode = $statusCode;
    }

    /**
     * @param $msg
     * @return void
     */
    public function errorWithLog($msg = null): void
    {
        $this->error($msg);
        Log::channel('response-error')->info($msg);
    }

    /**
     * @param $data
     * @return void
     */
    public function setData($data = []): void
    {
        foreach ($data as $key => $value) {
            if ($key != null && !empty($key)) {
                $this->res->$key = $value;
            }
        }
    }

    /**
     * @return JsonResponse
     */
    public function response(): JsonResponse
    {
        return response()->json($this->res, $this->res->statusCode);
    }
}
