<?php
namespace app\util;
// 努力只能及格，拼命才能优秀
class Res {
    public function success($msg,$data){
        return json([
            "code"=>200,
            "msg"=>$msg,
            "data"=>$data
        ]);
    }

    public function error($msg){
        return json([
            "code"=>400,
            "msg"=>$msg,
            "data"=>null
        ]);
    }
}
