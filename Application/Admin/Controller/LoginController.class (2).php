<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    //后台登录
    public function login(){
        layout(false);
        if(IS_POST){
            //表单提交
            $username = I('post.username');
            $password = I('post.password');
            $verify = I('post.verify');
            if(empty($username) && empty($password)){
                $this -> error('用户名或密码不能为空');
            }
            if(empty($verify)){
                $this -> error('验证码不能为空');
            }
            $model = new \Think\Verify();
            $res = $model -> check($verify);
            if(!$res){
                $this -> error('验证码错误');
            }
            $user = D("User") -> where(['username' => $username]) -> find();
            
            if($user && $user['password'] = $password){
                //登录成功
                session('Admin_info',$user);
                $this -> success('登录成功',U('Admin/Index/index'));
            }else{
                $this -> error('用户名或密码错误');
            }
        }
        $this -> display();
    }

    //ajax登录功能
    public function ajaxlogin(){
        layout(false);
        if(IS_POST){
            //表单提交
            $username = I('post.username');
            $password = I('post.password');
            $verify = I('post.verify');
            
            $model = new \Think\Verify();

            $res = $model -> check($verify);
            
            if(!$res){
                $return = array(
                    'code' => 10001,
                    'msg' => '验证码错误'
                    );
                $this -> ajaxReturn($return);
            }
            $user = D("Admin") -> where(['username' => $username]) -> find();
            
            if($user['username'] == $username && $user['password'] == $password){
                //登录成功
                session('Admin_info',$user);
                $return = array(
                    'code' => 10000,
                    'msg' => 'success',
                    );
                    $this -> ajaxReturn($return);
            }else{
                $return = array(
                    'code' => 10002,
                    'msg' => '帐号密码错误'
                    );
                $this -> ajaxReturn($return);
            }
        }
    }

    //验证码
    public function yanzhengma(){
        $data = array(
            'useCurve' => false,
            'useNoise' => false,
            'length' => 3,
            );
        $verify = new \Think\Verify($data);
        $verify -> entry();
    }

    //退出
    public function logout(){
        session(null);
        $this -> redirect('Admin/Index/login');
    }
}