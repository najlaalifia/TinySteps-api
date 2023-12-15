<?php

namespace App\Helpers;

use stdClass;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResponseFormatter
{
    protected static $response = [
        "body" => [
            'code' => "00",
            'info' => 'success',
            'data' => null
        ],
        "status" => 200
    ];

    public static function success($data = null, $message = null)
    {
        self::$response['body']['info'] = $message;
        self::$response['body']['data'] = $data;

        return response()->json(self::$response['body'], self::$response['status']);
    }

    public static function error($e = null, $message = "Saat ini server sedang error, silahkan coba lagi", $code = "-1")
    {
        if ($e instanceof HttpException) {
            self::$response["body"]['info'] = $message;
            if (app()->isLocal()) {
                self::$response["body"]['detail'] = $e->getMessage();
            }
            self::$response["status"] = $e->getStatusCode();
        } else {
            self::$response["body"]['info'] = $message;
            if (app()->isLocal() && $e != null) {
                try {
                    self::$response["body"]['detail'] = $e->getMessage();
                } catch (\Throwable $th) {
                    self::$response["body"]['detail'] = "Something went wrong.";
                }
            }
            self::$response["status"] = 500;
        }

        self::$response["body"]['code'] = $code;
        self::$response["body"]['data'] = new stdClass();
        return response()->json(self::$response["body"], self::$response["status"]);
    }
}
