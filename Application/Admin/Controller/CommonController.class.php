<?php
namespace Admin\Controller;
use Think\Cache\Driver\Db;
use Think\Controller;
class CommonController extends Controller{
    public function __construct()
    {
        parent::__construct();
        
        // if(!session('?Admin_info')){
        //     $this->redirect('Admin/Login/login');
        // }
        // if(session('Admin_info.status')== 0){
        //     $this->redirect('Admin/Login/login');
        // }
        // $this->checkauth();
        // $this->getnav();
    }

    // 设置用户自定义token
    protected function setToken()
    {
        if( C('USER_TOKEN_SWITCH') ){
            $guid = getGUID();
            session('_token', $guid);
            cookie('_token', $guid);
        }
    }

//    封装菜单栏左侧
    public function getnav(){
        if(session('?top')&&session('?second')){
            return false;
        }
        $role_id=session('Admin_info.role_id');
        if($role_id == 1){
//            超级管理员直接查询权限表
//            顶级权限
            $top=D('Auth')->where(['pid'=>0,'is_nav'=>1])->select();
            $second=D('Auth')->where(['pid'=>['gt',0],'is_nav'=>1])->select();
//            二级权限

        }else{
            $role=D('Role')->where(['role_id'=>$role_id])->find();
            $role_auth_ids=$role['role_auth_ids'];
            $top=D('Auth')->where("pid =0 and id in ($role_auth_ids) and is_nav=1" )->select();
            $second=D('Auth')->where("pid > 0 and id in ($role_auth_ids) and is_nav=1")->select();


        }
        session('top',$top);
        session('second',$second);

    }
//    检测权限
    public function checkauth(){
        $role_id=session('Admin_info.role_id');
        if($role_id ==1){
            return true;
        }
        $role=D('Role')->where(['role_id'=>$role_id])->find();
//        当前页面的控制器和方法
        $c=CONTROLLER_NAME;
        $a=ACTION_NAME;
        $ac=$c.'-'.$a;
//        if($ac == 'Index-index'){
//            return false;
//        }
        $auth_ac=explode(',',$role['role_auth_ac']);
        $auth_ac[]='Index-index';
        $auth_ac[]='Index-welcome';
        if(!in_array($ac,$auth_ac)){
           echo "<script> alert('无权访问')</script>";
            die;

//            $this->redirect('Admin/Index/index');
        }

    }
	
}