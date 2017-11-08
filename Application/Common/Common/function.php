<?php
//封装一个密码加密函数
function encrypt_password($password){
    //加盐
    $salt = 'djsdgfdkfdskafhds';
    return md5( $salt . md5($password) );
}
//电话加密
function encrypt_phone($phone)
{
    return substr($phone, 0, 3) . '****' . substr($phone, 7, 4);
}

//封装发送curl请求的函数
function curl_request($url, $post = false, $data = array(), $https = false){
    //初始化curl请求, 设置请求地址
    $ch = curl_init($url);
    //设置请求参数 针对post请求
    if($post){
        //发送post请求
        curl_setopt($ch, CURLOPT_POST, true);//设置请求方式为post
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置请求参数
    }
    //绕过https协议的证书校验
    if($https){
        //当前发送的是https协议的请求
        //禁用证书校验
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    //发送请求
    //直接返回结果
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);//成功 就是返回数据 失败 false
    //关闭请求 释放请求资源
    curl_close($ch);
    //返回数据
    return $result;
}

//#递归方法实现无限极分类
function getTree($list, $pid = 0, $level = 0)
{
    static $tree = array();
    foreach ($list as $row) {
        if ($row['pid'] == $pid) {
            $row['level'] = $level;
            $tree[] = $row;
            getTree($list, $row['id'], $level + 1);
        }
    }
    return $tree;
}

//递归查询子类id
function getTreeid($list, $pid = -1)
{
    if ($pid == -1) {
        return false;
    }
    static $tree = array();
    foreach ($list as $row) {
        if ($row['pid'] == $pid) {
            $tree[] = $row['id'];
            getTree($list, $row['id']);
        }
    }
    return $tree;
}
