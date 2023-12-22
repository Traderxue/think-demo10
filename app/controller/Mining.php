<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Miniing as MiningModel;
use app\util\Res;

class Mining extends BaseController
{
    protected $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();

        $mining = new MiningModel([
            "name" => $post["name"],
            "profit_rate" => $post["profit_rate"],
            "hash_rate" => $post["hash_rate"],
            "cycle" => $post["cycle"],
            "price" => $post["price"],
            "description" => $post["description"],
            "create_time" => date("Y-m-d H:i:s"),
            "total_amount" => $post["total_amount"]
        ]);
        $res = $mining->save();
        if ($res) {
            return $this->result->success("添加矿机成功", $res);
        }
        return $this->result->error("添加矿机失败");
    }

    //下架矿机
    public function remove($id)
    {
        $mining = MiningModel::where("id", $id)->find();
        $res = $mining->save([
            "status" => 0
        ]);
        if ($res) {
            return $this->result->success("下架成功", $res);
        }
        return $this->result->error("下架失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $name = $request->param("name");

        $list = MiningModel::where("name", "like", "%{$name}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);

        return $this->result->success("获取数据成功", $list);
    }

    public function getById($id)
    {
        $mining = MiningModel::where("id", $id)->find();
        return $this->result->success("获取数据成功", $mining);
    }

    public function edit(Request $request){
        $post = $request->post();
        $mining = MiningModel::where("id",$post["id"])->find();

        $res = $mining->save([
            "name"=>$post["name"],
            "profit_rate"=>$post["profit_rate"],
            "cycle"=>$post["cycle"],
            "price"=>$post["price"],
            "description"=>$post["description"],
            "total_amount"=>$post["total_amount"],
            "hash_rate"=>$post["hash_rate"],
        ]);

        if($res){
            return $this->result->success('编辑数据成功',$mining);
        }
        return $this->result->error("编辑数据失败");
    }

}
