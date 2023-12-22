<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Miniing as MiningModel;
use app\util\Res;

class Mining extends BaseController{
    protected $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function add(Request $request){
        $post = $request->post();

        $mining = new MiningModel([
            "name"=>$post["name"],
            "profit_rate"=>$post["profit_rate"],
            "hash_rate"=>$post["hash_rate"],
            "cycle"=>$post["cycle"],
            "price"=>$post["price"],
            "description"=>$post["description"],
            "create_time"=>date("Y-m-d H:i:s"),
            "total_amount"=>$post["total_amount"]
        ]);
        $res = $mining->save();
        if($res){
            return $this->result->success("添加矿机成功",$res);
        }
        return $this->result->error("添加矿机失败");
    }

    public function remove($id){
        
    }

}