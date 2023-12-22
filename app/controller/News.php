<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\News as NewsModel;
use app\util\Res;

class News extends BaseController
{
    protected $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();
        $news = new NewsModel([
            "add_time" => date("Y-m-d H:i:s"),
            "author" => $post["author"],
            "title" => $post["title"],
            "content" => $post["content"],
            "cover" => $post["cover"]
        ]);
        $res = $news->save();

        if ($res) {
            return $this->result->success("添加数据成功", $res);
        }
        return $this->result->error("添加数据失败");
    }

    public function deleteById($id)
    {
        $res = NewsModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除数据成功", $res);
        }
        return $this->result->error("删除数据失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $title = $request->param("title");

        $list = NewsModel::where("title", "like", "%{$title}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);

        return $this->result->success("获取数据成功", $list);
    }

    public function getById($id)
    {
        $new = NewsModel::where("id", $id)->find();
        return $this->result->success("获取数据成功", $new);
    }
}
