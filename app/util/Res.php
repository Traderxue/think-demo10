<?php
namespace app\util;

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