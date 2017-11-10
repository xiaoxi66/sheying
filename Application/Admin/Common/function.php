<?php
// 分类排序方法
function catePaixu($data = array(), $pid=0, $ceng=0)
{
    if( empty($data) )
        return;
	static $jieguo;
	foreach ($data as $k => $v) {
		if($v['pid'] == $pid){
			$data[$k]['ceng'] = $ceng;
            if( $ceng > 0 )
                $data[$k]['name'] = '|'. str_repeat('—', $ceng). $data[$k]['name'];
			$jieguo[] = $data[$k];
            unset($data[$k]);
			catePaixu($data, $v['id'], $ceng+1);
		}
	}
	return $jieguo;
}

// 生成guid
function getGUID()
{
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);
    $uuid = chr(123)
    .substr($charid, 0, 8).$hyphen
    .substr($charid, 8, 4).$hyphen
    .substr($charid,12, 4).$hyphen
    .substr($charid,16, 4).$hyphen
    .substr($charid,20,12)
    .chr(125);
    return $uuid;
}

// 进行表单验证
function tokenJudge()
{
    if( !session('?_token') || empty($_COOKIE['_token']) || cookie('_token') != session('_token') )
        return FALSE;
    return TRUE;
}