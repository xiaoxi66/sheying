<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller  {
//    添加
 public function add(){
     if(IS_POST){
         $data=I('post.');
         $model=D('Admin');
         $create=$model->create($data);
         if(!$create){
             $error=$model->getError();
             $ss=array(
                 'code'=>'10003',
                 'data'=>$error
             );
             $this->ajaxReturn($ss);
         }
         $res=$model->add($data);
         if($res){
             $ss=array(
                 'code'=>'10000',
                 'data'=>'添加成功'
             );
            $this->ajaxReturn($ss);
         }else{
             $ss=array(
                 'code'=>'10002',
                 'data'=>'添加失败'
             );
             $this->ajaxReturn($ss);
         }
     }else{
         $role=M('Role')->select();
         $this->assign('role',$role);
         $this->display();
     }


}
//展示
 public  function  showlist(){
//     展示用户
     $data=D('Admin')->alias('t1')
         ->field('t1.*,t2.role_name')
         ->join('left join web_role t2 on t2.role_id = t1.role_id ')
         ->select();
     $count=D('Admin')->count();
     $this->assign('count',$count);
     $this->assign('data',$data);
     $this->display();
 }
    //  修改
    public  function edit(){
        if(IS_POST){
            $data=I('post.');
            $admin=M('Admin');
            $res=$admin->save($data);

            if($res !==false){
                $ss=array(
                    'code'=>'10000',
                    'msg'=>'修改成功'
                );
                $this->ajaxReturn($ss);
            }else{
                $ss=array(
                    'code'=>'10001',
                    'msg'=>'修改失败'
                );
                $this->ajaxReturn($ss);
            }

        }else{
            $id=I('get.id',0,'intval');
            $admin=M('Admin');
            $data=$admin->find($id);
            $role=M('Role')->select();
            $this->assign('role',$role);
            $this->assign('data',$data);
            $this->display();
        }

    }

    //    删除
    public  function del(){
        $id=I('get.id');
        $admin=M('Admin');
        $role_id=$admin->where(['id'=>$id])->getField('role_id');
        if($role_id == 1){
            $return=array(
                'code'=>10002,
                'msg'=>'超级管理员不能删除'
            );
            $this->ajaxReturn($return);
//            $this->error('超级管理员不能删除');
        }
        $res=$admin->delete($id);
        if($res !== false){
            $return=array(
                'code'=>10000,
                'msg'=>'删除成功'
            );
            $this->ajaxReturn($return);
        }else{

            $return=array(
                'code'=>10001,
                'msg'=>'删除失败'
            );
            $this->ajaxReturn($return);
        }

    }
//超级管理员禁用某个管理员
    public function adminStop(){
        $id=I('post.id');
        $admin=M('Admin')->where(['id'=>$id])->find();
        $admin['status']=0;
        $res=M('Admin')->save($admin);
        if($res !==false){
            $ss=array(
                'code'=>'10000',
                'msg'=>'停用成功'
            );
            $this->ajaxReturn($ss);
        }else{
            $ss=array(
                'code'=>'10001',
                'msg'=>'停用失败'
            );
            $this->ajaxReturn($ss);
        }
    }
    //超级管理员开启某个管理员
    public function adminStart(){
        $id=I('post.id');
        $admin=M('Admin')->where(['id'=>$id])->find();
        $admin['status']=1;
        $res=M('Admin')->save($admin);
        if($res !== false){
            $ss=array(
                'code'=>'10000',
                'msg'=>'开启成功'
            );
            $this->ajaxReturn($ss);
        }else{
            $ss=array(
                'code'=>'10001',
                'msg'=>'开启失败'
            );
            $this->ajaxReturn($ss);
        }
    }
//展示管理员信息
    public function showinfo(){
        $id=I('get.id');
        $info=D('Admin')->where(['id'=>$id])->find();
        $str='';
        $str .='登录名:'.$info['username'].'<br/>';
        $str .='手机:'.$info['phone'].'<br/>';
        $str .='邮箱:'.$info['email'].'<br/>';
        $str .='加入时间:'.date('Y-m-d'.$info['create_time']).'<br/>';
        echo  htmlspecialchars_decode($str);
    }

/*一般形式：date('Y-m-d H:i:s', 1156219870);
2. 日期转换为UNIX时间戳用函数：strtotime()
一般形式：strtotime('2010-03-24 08:15:42')； */
}