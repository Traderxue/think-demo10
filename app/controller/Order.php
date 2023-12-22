<?php

namespace app\order;

use app\BaseController;
use think\Request;
use app\model\Order as OrderModel;
use app\model\User as UserModel;
use app\util\Res;
use think\facade\Db;

class Order extends BaseController
{
    protected $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();
        $order = new OrderModel;

        $res = $order->save([
            "u_id" => $post["u_id"],
            "add_time" => date("Y-m-d H:i:s"),
            "amount" => $post["amount"],
        ]);

        if ($res) {
            return $this->result->success("添加订单成功", $order);
        }
        return $this->result->error("添加订单失败");
    }

    function edit(Request $request)
    {
        $post = $request->post();
        $order = OrderModel::where("id", $post["id"])->find();
        $res = $order->save([
            "amound" => $post["amount"],
            "rate" => $post["rate"]
        ]);

        if ($res) {
            return $this->result->success("订单编辑成功", $order);
        }
        return $this->result->error("订单编辑失败");
    }

    function finish($id)
    {
        $order = OrderModel::where("id", $id)->find();
        $user = UserModel::where("id", $order->id)->find();

        Db::startTrans();
        try {
            $balance = (float) $user->balance + (float) $user->balance * $order->rate;

            $user->save([
                "balance" => $balance
            ]);

            $order->save([
                "finish"=>1
            ]);

            Db::commit();
            return $this->result->success("订单已完成",null);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->result->error("订单完成失败");
        }
    }

    function geByUid($u_id){
        $list = OrderModel::where("u_id",$u_id)->select();
        return $this->result->success("获取数据成功",$list);
    }

    function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        
        $list = OrderModel::paginate([
            "page"=>$page,
            "pageSize"=>$pageSize
        ]);

        return $this->result->success("获取分页数据成功",$list);
    }

    function deleteById($id){
        $res = OrderModel::where("id",$id)->delete();
        if($res){
            return $this->result->success("删除数据成功",$res);
        }
        return $this->result->error("删除数据失败");
    }
}
