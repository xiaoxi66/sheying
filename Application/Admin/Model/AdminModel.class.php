<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model{
    protected $_validate= array(
        array('username','require','用户名不能为空'),
        array('email','require','邮箱不能为空'),
        array('email','email','邮箱格式不正确'),
        array('email','','邮箱已被注册',0,'unique',3),
        array('phone','require','手机号不能为空'),
        array('phone','/\d{11}/','手机号格式不对'),
        array('phone','','手机号已被占用',0,'unique',3),
        array('password','require','密码不能为空'),
        array('repassword','require','确认密码不能为空'),
        array('password','6,20','密码长度必须在6-20',0,'length'),
        array('password','repassword','密码不一致',0,'confirm'),
    );
//    自动完成
    protected $_auto=array(
        array('create_time','time',1,'function'),
        array('password','md5',3,'function'),

    );
}