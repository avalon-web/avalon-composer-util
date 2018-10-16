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
    public static function get($url, $headers, $params, $timeout, $times)
    {
        return self::request("GET", $url, $headers, $params, $timeout, $times);
    }

    public static function post($url, $headers, $params, $timeout, $times)
    {
        return self::request("POST", $url, $headers, $params, $timeout, $times);
    }

    public static function patch($url, $headers, $params, $timeout, $times)
    {
        return self::request("PATCH", $url, $headers, $params, $timeout, $times);
    }

    public static function delete($url, $headers, $params, $timeout, $times)
    {
        return self::request("DELETE", $url, $headers, $params, $timeout, $times);
    }

    public static function put($url, $headers, $params, $timeout, $times)
    {
        return self::request("PUT", $url, $headers, $params, $timeout, $times);
    }

    public static function request($method, $url, $headers = ["Accept" => "application/json; charset=utf-8"], $params, $timeout = 10, $times = 1)
    {
        $client = new Client();
        $status = 408;
        $message = "";
        try {
            $res = $client->request($method, $url,
                [
                    "headers" => $headers,
                    "form_params" => $params,
                    "timeout" => $timeout
                ]
            );
            $status = $res->getStatusCode();
            $message = json_decode($res->getBody()->getContents());
            if ((int)floor($status / 100) !== 2) {
                throw new Exception($res);
            }
        } catch (Exception $e) {
            $times--;
            if ($times > 0) {
                return self::request($method, $url, $headers, $params, $timeout, $times);
            }
        }
        return ["status" => $status, "message" => $message];
    }
}