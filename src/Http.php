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
    public static function get($options)
    {
        $options["method"] = "GET";
        return self::request($options);
    }

    public static function post($options)
    {
        $options["method"] = "POST";
        return self::request($options);
    }

    public static function patch($options)
    {
        $options["method"] = "PATCH";
        return self::request($options);
    }

    public static function delete($options)
    {
        $options["method"] = "DELETE";
        return self::request($options);
    }

    public static function put($options)
    {
        $options["method"] = "PUT";
        return self::request($options);
    }

    public static function request($options)
    {
        $defaultOptions = [
            "headers" => ["Content-Type" => "application/json; charset=utf-8"],
            "params" => [],
            "timeout" => 10,
            "times" => 1
        ];
        $options = array_merge($defaultOptions, $options);
        ["method" => $method, "url" => $url, "headers" => $headers, "params" => $params, "timeout" => $timeout, "times" => $times] = $options;
        $client = new Client();
        $status = 408;
        $message = "";
        try {
            $res = $client->request($method, $url,
                [
                    "headers" => $headers,
                    "json" => $params,
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
            $options["times"] = $times;
            if ($times > 0) {
                return self::request($options);
            }
        }
        return ["status" => $status, "message" => $message];
    }
}