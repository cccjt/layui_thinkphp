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

class Menu extends AdminBaseController
{
    public function index()
    {
        $input = Request::post();

        $res['rows'] = Db::table('wechat_member')->limit($input['limit'])->page($input['page'])->order($input['field'], $input['order'])->select();
        $res['total'] = Db::table('wechat_member')->count();
        return $res;
    }

    public function JsonPretiy()
    {
        return json_encode(Request::post(),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

}