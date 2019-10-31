<?php
// +----------------------------------------------------------------------
// | 99PHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2020 https://www.99php.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Mr.Chung <chung@99php.cn >
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use think\Db;
use think\exception\HttpResponseException;
use app\common\service\AuthService;

/**
 * 后台基础控制器
 * 暂时使用admin鉴权!!!!!!
 * Class AdminBaseController
 * @package controller
 */
class AdminBaseController extends Controller
{
    /**
     * 初始化数据
     * Index constructor.
     */
    public function initialize()
    {
        parent::initialize();

    }

    public function _empty()
    {
        return ['code'=>404, 'msg'=>'no find', 'data'=>''];
    }

}