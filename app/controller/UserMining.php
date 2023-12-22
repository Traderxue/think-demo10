<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\UserMining as UserMiningModel;
use app\util\Res;

class UserMining extends BaseController
{
    protected $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();

        $user_mining = new UserMiningModel([
            "u_id" => $post["u_id"],
            "mining_id" => $post["mining_id"],
            "num" => $post["num"],
            "buy_time" => $post["Y-m-d H:i:s"],
            "profit" => $post["profit"]
        ]);

        $res = $user_mining->save();

        if ($res) {
            return $this->result->success("添加数据成功", $res);
        }
        return $this->result->error("添加数据失败");
    }

    public function edit(Request $request)
    {
        $post = $request->post();

        $user_mining = UserMiningModel::where("id", $post["id"])->find();

        $res = $user_mining->save([
            "num" => $post["num"],
            "profit" => $post["profit"]
        ]);

        if ($res) {
            return $this->result->success("编辑数据成功", $res);
        }
        return $this->result->error("编辑数据失败");
    }

    public function deleteById($id)
    {
        $res = UserMiningModel::where("id", $id)->delete();

        if ($res) {
            return $this->result->success("删除数据成功", $res);
        }
        return $this->result->error("删除数据失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");

        $list = UserMiningModel::paginatep([
            "page" => $page,
            "list_rows" => $pageSize
        ]);

        return $this->result->success("获取分页数据成功", $list);
    }
}
