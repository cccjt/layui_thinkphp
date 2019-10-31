<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/4/2
 * Time: 23:52
 */

namespace app\admin\controller\wechat;

use app\common\controller\AdminBaseController;
use think\Db;
use think\facade\Request;

/**
 * @AuthName 粉丝管理
 * Class Member
 * DataTime 2019/8/5 18:28
 * @package app\admin\controller
 */
class Member extends AdminBaseController
{
    /**
     * @AuthName 粉丝管理
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $input = Request::post();

        $res['rows'] = Db::table('wechat_member')->limit($input['limit'])->page($input['page'])->order($input['field'], $input['order'])->select();
        $res['total'] = Db::table('wechat_member')->count();
        return $res;
    }

}