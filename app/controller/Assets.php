<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Assets as AssetsModel;
use app\util\Res;

//用户资金记录
class Assets extends BaseController
{
    public $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();
        $assets = new AssetsModel([
            "u_id" => $post["u_id"],
            "amount" => $post["amount"],
            "add_time" => date("Y-m-d H:i:s"),
            "operate" => $post["operate"]
        ]);

        $res = $assets->save();
        if ($res) {
            return $this->result->success("添加记录成功", $assets);
        }
        return $this->result->error("添加记录失败");
    }

    public function getByUid($u_id)
    {
        $list = AssetsModel::where("u_id", $u_id)->select();
        return $this->result->success("获取用户订单数据成功", $list);
    }

    public function deleteById($id)
    {
        $res = AssetsModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除订单成功", $res);
        }
        return $this->result->error("删除订单失败");
    }

    //审核
    public function verfiy($id)
    {
        $assets = AssetsModel::where("id",$id)->find();
        $res = $assets->save();
        if($res){
            return $this->result->success("审核通过",$assets);
        }
        return $this->result->error("审核失败");
    }

    public function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $operate = $request->param("operate");
        $list = AssetsModel::where("operate","like","%{$operate}%")->paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);

        return $this->result->success("获取分页数据成功",$list);
    }
}
