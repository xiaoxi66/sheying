<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index()
    {
        $this->display('Index/index');
    }

    // 后台 统计 页面
    public function index_v1()
    {
		$this->display('Index/index_v1');
    }

    // 后台 通知 页面
    public function mailbox()
    {
    	$this->display('Index/mailbox');
    }

    // 后台 消息 页面
    public function notifications()
    {
    	$this->display('Index/notifications');
    }
}