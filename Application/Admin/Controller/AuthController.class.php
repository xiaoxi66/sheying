<?php
namespace Admin\Controller;
use Think\Controller;
class AuthController extends Controller  {
    public function showlist(){
        $auth=D('Auth')->select();
        $count=D('Auth')->count();
        $this->assign('count',$count);
        $auth=getTree($auth);
        $this->assign('auth',$auth);
//        $this->assign('auth',json_encode($auth));
        $this->display();
    }
//    添加权限
    public function add(){
        if(IS_POST){
            $data=I('post.');
            $model=D('Auth');
            $create=$model->create($data);
            if(!$create){
                $error=$model->getError();
                $this->error($error);
            }
            $res=$model->add();
            if($res){
//                $this->success('添加成功',U('Admin/Auth/showlist'));
                $return=array(
                    'code'=>10000,
                    'msg'=>'success'
                );
                $this->ajaxReturn($return);
            }else{
                $return=array(
                    'code'=>10001,
                    'msg'=>'添加失败'
                );
                $this->ajaxReturn($return);
            }

        }else{
            $top=D('Auth')->where("pid=0")->select();
            $this->assign('top',$top);
            $this->display();
        }
    }
//    权限修改
    public function edit(){
        if(IS_POST){
            $data=I('post.');
            $model=D('Auth');
            $res=$model->save($data);
            if($res !== false){
                $return=array(
                    'code'=>10000,
                    'msg'=>'success'
                );
                $this->ajaxReturn($return);
            }else{
                $return=array(
                    'code'=>10001,
                    'msg'=>'faill'
                );
                $this->ajaxReturn($return);
            }

        }else{
            $id=I('get.id',0,'intval');
            if($id <=0){
                $this->error('参数错误');
            }
            $top=D('Auth')->where("pid=0")->select();
            $this->assign('top',$top);
            $auth=D('Auth')->where(['id'=>$id])->find();
            $auth_info=D('Auth')->select();
//            判断是否有子类，
            if($auth.$id==0 ){
                $ids=getTreeid($auth_info,$id);
                if($ids){
                    $state=true;
                }else{
                    $state=false;
                }
            }
            $this->assign('state',$state);
            $this->assign('auth',$auth);
            $this->display();
        }
    }
    //    权限删除
    public function del(){
        $id=I('get.id',0,'intval');
        if($id <=0){
            $return=array(
                'code'=>10001,
                'msg'=>'参数错误'
            );
            $this->ajaxReturn($return);
        }
        $auth=D('Auth')->select();
//        递归遍历下面是否还有权限
        $ids=getTreeid($auth,$id);
        if(count($ids)){
            $return=array(
                'code'=>10002,
                'msg'=>'有子权限不能删除'
            );
            $this->ajaxReturn($return);
        }
        $res=D('Auth')->delete($id);
        if($res){
            $return=array(
                'code'=>10000,
                'msg'=>'删除成功'
            );
            $this->ajaxReturn($return);
        }else{

            $return=array(
                'code'=>10003,
                'msg'=>'删除失败'
            );
            $this->ajaxReturn($return);
        }


    }

}