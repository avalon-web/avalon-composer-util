<?php

namespace AvalonComposerUtil;

class RegexParam
{
    //正则验证参数
    public static function check($paramsArr, $request)
    {
        $arr = $request->post();
        $invalidParam = null;
        for ($i = 0; $i < sizeof($paramsArr); $i++) {
            $d = $paramsArr[$i];
            ["id" => $id] = $d;
            //未包含指定的参数
            if (!array_key_exists($id, $arr)) {
                $invalidParam = $id;
                break;
            }
            if (!array_key_exists("regex", $d)) {
                $regex = $d["regex"];
                //如果包含regex属性,则需要进行正则验证
                if (!preg_match($regex, $arr[$id])) {
                    $invalidParam = $id;
                }
            }
        }
        return $invalidParam;
    }
}