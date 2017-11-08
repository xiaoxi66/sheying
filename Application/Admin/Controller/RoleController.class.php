<?php
namespace Admin\Controller;
use Think\Controller;
class RoleController extends Controller  {
    public function showlist(){
        //        添加页码
        $count = D('Role')->count();
        $per = 10;
        $page = new \Think\Page($count, $per);
//        public $lastSuffix = true; // 最后一页是否显示总页数
        $page->setConfig('prev','Prev');
        $page->setConfig('next','Next');
        $page->lastSuffix = false;

        $page->rollPage = 7;
        $pagelist = $page->show();
        $this->assign('pagelist', $pagelist);
        $data=D('Role')->limit($page->firstRow, $page->listRows)->select();
        foreach ($data as $k => &$v){
            $admin=M('Admin')->field('id,username')->where(['role_id'=>$v['role_id']])->select();
            $v['user_name']=$admin;
        }
        $this->assign('count',$count);
        $this->assign('data',$data);
        $this->display();
    }
//    角色添加
    //    添加角色
    public function add(){
        if(IS_POST){
            $data=I('post.');
            $res=D('Role')->add($data);
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
            $this->display();
        }
    }
    //    修改角色名
    public function edit(){
        if(IS_POST){
            $data=I('post.');
            $model=D('Role');
            $res=D('Role')->save($data);
            if($res !== false){
                $return=array(
                    'code'=>10000,
                    'msg'=>'修改成功'
                );
                $this->ajaxReturn($return);
            }else{
                $return=array(
                    'code'=>10001,
                    'msg'=>'修改失败'
                );
                $this->ajaxReturn($return);
            }
        }else{
            $role_id=I('get.role_id',0,'intval');
            if($role_id <=0){
                $this->error('参数错误');
            }
            $role_info=D('Role')->where(['role_id'=>$role_id])->find();
            $this->assign('role_info',$role_info);
            $this->display();
        }
    }
    //    删除
    public  function del(){
        $role_id=I('get.role_id',0,'intval');
        if($role_id <=0){
            $return=array(
                'code'=>10001,
                'msg'=>'参数错误'
            );
            $this->ajaxReturn($return);
        }
//        判断角色中是否有管理员存在，有则不能删除
        $res=D('Admin')->where(['role_id'=>$role_id])->count();
        if($res){
            $return=array(
                'code'=>10002,
                'msg'=>'角色有人占用，不能删除'
            );
            $this->ajaxReturn($return);
        }
        $res=D('Role')->where(['role_id'=>$role_id])->delete();
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
    //分配权限
    public  function setauth(){
        if(IS_POST){
            $role_id=I('post.role_id');
            $auth_ids=I('post.id');
            $model=D('Role');
            $data['role_id']=$role_id;
            $data['role_auth_ids']=implode(',',$auth_ids);
            $data['role_auth_ac']='';
            $auth=D('Auth')->where("id in ( {$data['role_auth_ids']} )")->select();
            foreach ($auth as $v){
                if($v['auth_c']&&$v['auth_a']){
                    $data['role_auth_ac'].=$v['auth_c'].'-'.$v['auth_a'].',';
                }
            }
            $data['role_auth_ac']=rtrim($data['role_auth_ac'],',');
            $res=$model->save($data);
            if($res !== false){
                $return=array(
                    'code'=>10000,
                    'msg'=>'修改成功'
                );
                $this->ajaxReturn($return);
            }else{
                $return=array(
                    'code'=>10001,
                    'msg'=>'修改失败'
                );
                $this->ajaxReturn($return);
            }
        }else{
            $id=I('get.role_id');
            $role=D('Role')->where(['role_id'=>$id])->find();
            $this->assign('role',$role);
//            所有顶级权限
            $top_all=D("Auth")->where("pid =0")->select();
//            所有二级权限
            $second_all=D('Auth')->where('pid > 0')->select();
            $this->assign('top_all',$top_all);
            $this->assign('second_all',$second_all);
            $this->display();
        }
    }

}