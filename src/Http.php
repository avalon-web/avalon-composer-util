<?php
/**
 * Created by PhpStorm.
 * User: anshi
 * Date: 2018/10/10
 * Time: 16:42
 */

namespace AvalonComposerUtil;

use GuzzleHttp\Client;
use Mockery\Exception;

class Http
{
    public static function get($url, $headers, $params, $timeout)
    {
        return self::request("GET", $url, $headers, $params, $timeout);
    }

    public static function post($url, $headers, $params, $timeout)
    {
        return self::request("POST", $url, $headers, $params, $timeout);
    }

    public static function patch($url, $headers, $params, $timeout)
    {
        return self::request("PATCH", $url, $headers, $params, $timeout);
    }

    public static function delete($url, $headers, $params, $timeout)
    {
        return self::request("DELETE", $url, $headers, $params, $timeout);
    }

    public static function put($url, $headers, $params, $timeout)
    {
        return self::request("PUT", $url, $headers, $params, $timeout);
    }

    public static function request($method, $url, $headers = ["Accept" => "application/json; charset=utf-8"], $params, $timeout = 10)
    {
        $client = new Client();
        $res = $client->request($method, $url,
            [
                "headers" => $headers,
                "form_params" => $params,
                "timeout" => $timeout
            ]
        );
        $status = $res->getStatusCode();
        if ((int)floor($status / 100) !== 2) {
            throw new Exception($res);
        }
        return ["status" => $status, "message" => $res];
    }
}