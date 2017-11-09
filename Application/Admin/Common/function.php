<?php
// 分类排序方法
function catePaixu($data = array(), $pid=0, $ceng=1)
{
	static $jieguo = array();
	foreach ($data as $k => $v) {
		if($v['pid'] = $pid){
			$data[$k]['ceng'] = $ceng;
			$jieguo[] = $data[$k];
			catePaixu($data, $v['pid'], $ceng++);
		}
	}
	return $jieguo;
}

// 生成guid
function getGUID() {
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