<?php
/**
 * Created by PhpStorm.
 * Author: huxinlu
 * Date: 2017/12/5
 * Time: 13:40
 */
namespace app\comment\validate;

use think\Validate;

class CommentValidate extends Validate
{
    protected $rule = [
        ['page', 'require|integer', '页数不能为空|页数必须是整数'],
        ['limit', 'require|integer', '每页显示数不能为空|每页显示数必须是整数'],
        ['comment', 'require|max:200', '评论内容不能为空|评论内容不能超过200个字符'],
        ['score', 'require|integer|between:0,5', '评分不能为空|评分必须为整数|评分必须在0-5之间'],
    ];

    protected $scene = [
        'comment' => ['comment'],
        'drug'    => ['comment', 'score'],
        'list'    => ['page', 'limit'],
    ];
}