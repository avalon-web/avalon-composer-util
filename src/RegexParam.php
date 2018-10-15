<?php

namespace AvalonComposerUtil;

class RegexParam
{
    //正则验证参数
    public static function check($paramsArr, $request)
    {
        $method = $request->getMethod();
        $arr = $method === "GET" ? $request->query() : $request->post();
        $paramsArr = new MyArray($paramsArr);
        //找出不符合要求的参数
        $findEle = $paramsArr->find(function ($d) use ($arr) {
            ["id" => $id] = $d;
            //如果是可选参数,并且不存在,则不做正则判断
            if(array_key_exists("optional",$arr) && $d["optional"] === true && !array_key_exists($id, $arr)){
                return false;
            }

            //判断参数是否存在
            if (array_key_exists($id, $arr)) {
                if (array_key_exists("regex", $d)) {
                    $regex = $d["regex"];
                    //如果包含regex属性,则需要进行正则验证
                    return !preg_match($regex, $arr[$id]);
                } else {
                    //未包含正则,不验证
                    return false;
                }
            } else {
                return true;
            }
        });
        return $findEle;
    }
}