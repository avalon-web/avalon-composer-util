<?php

namespace AvalonComposerUtil;

class Signature
{
    public static function getMd5Sign($params_arr, $key)
    {
        $key_arr = new MyArray(array_keys($params_arr));
        //排除从url中获取的XDEBUG_SESSION_START参数
        $key_arr = $key_arr->filter(function ($d) {
            return $d !== "XDEBUG_SESSION_START";
        });
        //按小写的key进行排序
        $key_arr->sort(function ($a, $b) {
            $a = strtolower($a);
            $b = strtolower($b);
            if ($a < $b) {
                return -1;
            }
            if ($a > $b) {
                return 1;
            }
            return 0;
        });
        //对排序后的数组的值连接，末尾追加key字符串进行md5签名
        $value_str = $key_arr->map(function ($d) use ($params_arr) {
            $v = $params_arr[$d];
            $type = gettype($v);
            switch ($type) {
                case "boolean":
                    return $v === true ? "true" : "false";
                    break;
                case "array":
                    return json_encode($v);
                    break;
                default:
                    return $v;
                    break;
            }
        })->join("");
        $signature = "Bearer " . md5($value_str . $key);
        return $signature;
    }
}