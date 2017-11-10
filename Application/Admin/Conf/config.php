<?php
return array(
		//'配置项'=>'配置值'
//    pathinfo模式，一般用/不用./图片上传的时候
    "URL_MODEL" =>1,
//    页面追踪
//    "SHOW_PAGE_TRACE" => true,
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'sheying',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'web_',    // 数据库表前缀

    // 自定义令牌开关
    'USER_TOKEN_SWITCH'     =>  true,
);