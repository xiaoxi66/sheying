<?php
namespace Admin\Controller;

class ClothingController extends CommonController
{
	public function index()
	{
		dump(1);
	}

	// 展示分类页面
	public function classify()
	{
		if( IS_AJAX ){
			$data['data'] = catePaixu( M('cate')->select() );
			$data['count'] = M('cate')->field('count("id") as count')->find()['count'];
			if( !empty($data['data']) ){
				$data['code'] = 0;
				$data['msg'] = '';
			}else{
				$data['code'] = 1;
				$data['msg'] = '查询失败';
			}
			if( $data['count'] == 0 )
				$data['msg'] = '暂无数据';
			$this->ajaxReturn( $data );
		}else{
			$this->display('Clothing/classify');
		}
	}

	// 展示分类新增页面
	public function classifyAdd()
	{
		if( IS_AJAX ){
			
			$this->ajaxReturn( $data );
		}else{
			$this->assign('data', M('cate')->field('id,name')->where('pid=0')->select() );
			$this->display('Clothing/classifyAdd');
		}
	}

	
}