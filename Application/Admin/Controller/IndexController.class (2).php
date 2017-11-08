<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController  {
    public function index(){
        $role_id=session('Admin_info.role_id');
        $admin_role=D('Role')->where(['role_id'=>$role_id])->find();
        $this->assign('admin_role',$admin_role);
    	$this -> display();
    }
    public function welcome(){
    	$data = $_SERVER;
    	$this -> assign('data',$data);
        $this->display();
    }
}