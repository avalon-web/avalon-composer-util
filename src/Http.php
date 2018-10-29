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
            //            $curl_handle = curl_init();
//            curl_setopt_array($curl_handle, [
//                CURLOPT_URL => $url,
//                CURLOPT_CUSTOMREQUEST => $method,
//                CURLOPT_HEADER => $headers,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_POST => true,
//                CURLOPT_POSTFIELDS => '{"order_id":"eizotAh9yUwkhody4wj8feCeFCV50plY","is_sandbox":2,"trans_params":"{\"productId\":215,\"version\":\"1.3.0.0\",\"subChannel\":0,\"zone\":0}"}',
//                CURLOPT_TIMEOUT => $timeout,
//                CURLOPT_CONNECTTIMEOUT => $timeout,
//
//            ]);
//            $response_json = curl_exec($curl_handle);
//            $status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
//            $message = json_decode($response_json);
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
            $options["times"] = $times;
            if ($times > 0) {
                return self::request($options);
            }
        }
        return ["status" => $status, "message" => $message];
    }
}