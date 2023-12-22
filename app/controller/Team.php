<?php

namespace app\controller;

use think\Request;
use app\model\Team as TeamModel;
use app\util\Res;
use app\BaseController;

class Team extends BaseController
{
    protected $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function upgrade($id)
    {
        $team = TeamModel::where("id", $id)->find();
        $grade = $team->grade;
        $res = $team->save([
            "grade" => $grade + 1
        ]);
        if ($res) {
            return $this->result->success("团队升级成功", $res);
        }
        return $this->result->error("团队升级失败");
    }
}
