<?php
namespace Admin\Model;
use Think\Model;
class CateModel extends Model
{
	// 字段映射
	// protected $_map = array(
	// 	'name' => 'name',
	// 	'pid' => 'pid',
	// );

	// 自动验证
	protected $_validate = array(
		array('name', 'require', '分类名称不能为空', 1, 'regex', 3),
		// array('name', '/[a-zA-Z0-9\u4E00-\u9FA5]+/', '分类名称仅支持中文字母数字', 1, 'regex', 3),
		array('name', 'require', '分类名称重复，请重试', 1, 'unique', 3),
		array('pid', 'require', '请选择上级分类', 1, 'regex', 3),
	);
}