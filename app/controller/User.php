<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\util\Email;
use app\util\Res;
use app\model\User as UserModel;
use Firebase\JWT\JWT;

class User extends BaseController
{
    protected $result;
    protected $email;
    public function __construct(\think\App $app)
    {
        $this->result = new Res();
        $this->email = new Email();
    }

    public function getCode(Request $request)
    {
        $post = $request->post();
        $code = rand(10000, 99999);

        if (!$post["email"]) {
            return $this->result->error("邮箱不能为空");
        }

        session_start();
        $_SESSION['verification_code'] = $code;

        $res = $this->email->sendEmail($code, $post['email']);

        if ($res) {
            return $this->result->success("邮件发送成功", $res);
        }
    }

    public function register(Request $request)
    {
        $post = $request->post();

        if (!$post["username"] || !$post["password"]) {
            return $this->result->error("用户名密码不能为空");
        }

        if (!$post["email"]) {
            return $this->result->error("邮箱不能为空");
        }

        $u = UserModel::where("username", $post["username"])->find();

        if ($u) {
            return $this->result->error("用户已存在");
        }

        session_start();
        if ($_SESSION['verification_code'] != $post["code"]) {
            return $this->result->error("验证码错误");
        }

        $user = new UserModel([
            "username" => $post["username"],
            "password" => password_hash($post["password"], PASSWORD_DEFAULT),
            "email" => $post["email"],
            "invite_code" => $post["invite_code"]
        ]);

        $res = $user->save();

        if ($res) {
            unset($_SESSION['verification_code']);
            return $this->result->success("用户注册成功", $user);
        }
        return $this->result->error("用户注册失败");
    }

    function login(Request $request)
    {
        $username = $request->post("username");
        $password = $request->post("password");

        $user = UserModel::where("username", $username)->find();

        if (!$user) {
            return $this->result->error("用户不存在");
        }

        if (password_verify($password, $user->password)) {
            $secretKey = '123456789'; // 用于签名令牌的密钥，请更改为安全的密钥

            $payload = array(
                // "iss" => "http://127.0.0.1:8000",  // JWT的签发者
                // "aud" => "http://127.0.0.1:9528/",  // JWT的接收者可以省略
                "iat" => time(),  // token 的创建时间
                "nbf" =>  time(),  // token 的生效时间
                "exp" => time() + 3600,  // token 的过期时间
                "data" => [
                    // 包含的用户信息等数据
                    "username" => $username,
                ]
            );
            // 使用密钥进行签名
            $token = JWT::encode($payload, $secretKey, 'HS256');
            return $this->result->success("登录成功", $token);
        }
        return $this->result->error("用户名或密码错误");
    }

    function resetPwd(Request $reqeust)
    {
        $code = $reqeust->post("code");
        $username = $reqeust->post("username");
        $new_password = $reqeust->post("new_password");

        $user = UserModel::where("username", $username)->find();

        session_start();
        if ($_SESSION['verification_code'] != $code) {
            return $this->result->error("验证码错误");
        }

        $res = $user->save([
            "password" => password_hash($new_password, PASSWORD_DEFAULT)
        ]);

        if ($res) {
            return $this->result->success("修改密码成功", $res);
        }
        return $this->result->error("修改密码失败");
    }

    function deleteById($id)
    {
        $res = UserModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除用户成功", $res);
        }
        return $this->result->error("删除用户失败");
    }

    function disabled($id)
    {
        $user = UserModel::where("id", $id)->find();
        $res = $user->save([
            "disabled" => 1
        ]);
        if ($res) {
            return $this->result->success("禁用成功", $res);
        }
        return $this->result->error("禁用用户失败");
    }

    function page(Request $request)
    {
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $username = $request->param("username");

        $list = UserModel::where("username", "like", "%{$username}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize,
        ]);
        return $this->result->success("获取分页数据成功", $list);
    }
}
