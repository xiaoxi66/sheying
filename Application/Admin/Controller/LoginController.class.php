<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller
{
	public function login(){
		$this->display();
	}
	// ajax登录
	public  function ajaxlogin(){
            $username=I('post.username');
            $password=I('post.password');
            $code=I('post.verify');
            $v= new \Think\Verify();
            $check=$v->check($code);
            $is_rem = $_POST['is_rem'] ? : '';
            if(!$check){
                $return=array(
                    'code'=>10001,
                    'msg'=>'验证码错误',
                );
                $this->ajaxReturn($return);
            }

            $info=D('Manager')->where(['username'=>$username])->find();
            if($info&&$info['password']==salt_md5($password)){
                session('manager_info',$info);
                // 记住用户名
                if ($is_rem) {
                    cookie('user', $username, ['expire' => 86400]);
                }
                $return=array(
                    'code'=>10000,
                    'msg'=>'success',
                );
             
                $this->ajaxReturn($return);

            }else{

                $return=array(
                    'code'=>10002,
                    'msg'=>'用户名或密码错误',
                );
               
                $this->ajaxReturn($return);

            }

    }
    // 登出
    public  function logout(){
        session(null);
        $this->redirect('Admin/Login/login');
    }
    // 验证码
    public function Captche(){
        $config=array(
            'useCurve'  =>  true,            // 是否画混淆曲线
            'useNoise'  =>  true,            // 是否添加杂点
            'length'    =>  4,               // 验证码位数
        );

         $verify = new \Think\Verify($config);
        $verify->entry();


    }
	
	
}