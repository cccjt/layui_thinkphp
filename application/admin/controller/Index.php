<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/4/2
 * Time: 23:52
 */

namespace app\admin\controller;

use app\common\model\SysConfig;
use think\facade\Request;

/**
 * Class Index
 * DataTime 2019/8/5 18:28
 * @package app\admin\controller
 */
class Index extends \app\common\controller\AdminBaseController
{

    public function fun()
    {
        dump(Request::action());
        dump(123);
    }

    public function fun2()
    {

    }


}