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
			if( !tokenJudge() )
				$this->ajaxReturn( ['code'=>678] );
			$shuju = M('cate')->alias('a')->field('a.*,b.name as classify')->join('left join web_cate b on a.pid=b.id')->select();
			$data['data'] = catePaixu( $shuju );
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
			$this->setToken();
			$this->display('Clothing/classify');
		}
	}

	// 展示分类新增页面
	public function classifyAdd()
	{
		if( IS_AJAX ){
			if( !tokenJudge() )
				$this->ajaxReturn( ['code'=>678] );
			if( I('post.pid', '', 'intval') === '' )
				$this->ajaxReturn( ['code'=>680, 'msg'=>'请选择上级分类'] );
			$data = I('post.');
			if( $data['pid'] != 0 ){
				$pid = M('cate')->field('pid')->where('id='. $data['pid'])->find();
				if( !$pid || $pid['pid'] != 0 )
					$this->ajaxReturn( ['code'=>679, 'msg'=>'仅支持二级分类'] );
			}
			$model = D('cate');
			if( $jieguo = $model->create( $data ) ){
				if( $model->add() )
					$this->ajaxReturn( ['code'=>666, 'msg'=>'添加成功'] );
				else 
					$this->ajaxReturn( ['code'=>681, 'msg'=>'添加失败'] );
			}
			$this->ajaxReturn( ['code'=>682, 'msg'=>$model->getError()] );
		}else{
			$this->setToken();
			$this->assign('data', M('cate')->field('id,name')->where('pid=0')->select() );
			$this->display('Clothing/classifyAdd');
		}
	}

	// 展示修改页面
	public function classifyEdit()
	{
		if( IS_AJAX ){
			if( !tokenJudge() )
				$this->ajaxReturn( ['code'=>678] );
			if( I('post.pid', '', 'intval') === '' )
				$this->ajaxReturn( ['code'=>680, 'msg'=>'请选择上级分类'] );
			$data = I('post.');
			$oldData = M('cate')->where('id='. $data['id'])->find();
			if( $oldData['id'] == $data['pid'] )
				$this->ajaxReturn( ['code'=>685, 'msg'=>'上级分类不能选择自己'] );
			if( empty($oldData) )
				$this->ajaxReturn( ['code'=>683, 'msg'=>'修改失败'] );
			if( $oldData['pid'] == 0 && $data['pid'] != 0 )
				if( M('cate')->field('count("id") as count')->where('pid='. $data['id'])->find()['count'] > 0 )
					$this->ajaxReturn( ['code'=>684, 'msg'=>'该顶级分类下还有二级分类不支持修改'] );
			if( $data['pid'] != 0 ){
				$pid = M('cate')->field('pid')->where('id='. $data['pid'])->find();
				if( !$pid || $pid['pid'] != 0 )
					$this->ajaxReturn( ['code'=>679, 'msg'=>'仅支持二级分类'] );
			}
			$model = D('cate');
			if( $jieguo = $model->create( $data ) ){
				if( $model->save() )
					$this->ajaxReturn( ['code'=>666, 'msg'=>'编辑成功'] );
				else 
					$this->ajaxReturn( ['code'=>681, 'msg'=>'编辑失败'] );
			}
			$this->ajaxReturn( ['code'=>682, 'msg'=>$model->getError()] );
		}else{
			$id = I('get.id', '', 'intval');
			if( empty($id) )
				$this->ajaxReturn( ['code'=>678] );
			$alone = M('cate')->where('id='. $id)->find();
			if( empty($alone) )
				$this->ajaxReturn( ['code'=>679] );
			$this->setToken();
			$this->assign('alone', $alone );
			$this->assign('data', M('cate')->field('id,name')->where('pid=0')->select() );
			$this->display('Clothing/classifyEdit');
		}
	}

	// 处理分类删除
	// 没有软删除，美中不足
	public function classifyDel()
	{
		if( !IS_AJAX ) die(LAONAINAI);
		if( !tokenJudge() )
			$this->ajaxReturn( ['code'=>678] );
		$id = I('post.id', '', 'intval');
		if( empty($id) )
			$this->ajaxReturn( ['code'=>681, 'msg'=>LAONAINAI] );
		$data = M('cate')->field('id,pid')->where('id='. $id)->find();
		if( empty($data) )
			$this->ajaxReturn( ['code'=>679, 'msg'=>'删除失败，暂无此分类数据'] );
		if( $data['pid'] == 0 && M('cate')->field('count("id") as count')->where('pid='. $data['id'])->find()['count'] > 0 )
			$this->ajaxReturn( ['code'=>680, 'msg'=>'该顶级分类下还有二级分类不支持删除'] );
		if( M('cate')->where('id='. $id)->delete() )
			$this->ajaxReturn( ['code'=>666, 'msg'=>'删除成功'] );
		$this->ajaxReturn( ['code'=>682, 'msg'=>'删除失败，请稍后重试'] );
	}

	
}