<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/8/5
 * Time: 1:04
 */

namespace app\admin\controller\system;

use app\common\controller\AdminBaseController;
use think\Db;
use think\facade\Request;

/**
 * @AuthName 系统用户
 * Class Aduser
 * DataTime 2019/8/6 1:34
 * @package app\admin\controller\system
 */
class Aduser extends AdminBaseController
{

    /**
     * @AuthName 系统用户
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $res = [];
        $res['rows'] = Db::table('system_aduser')->select();
        foreach ($res['rows'] as $k => $v) {
            $res['rows'][$k]['role_name'] = Db::table('system_group')->where('id', $v['role_id'])->value('name');
        }
        $res['total'] = Db::table('system_aduser')->count();
        return $res;
    }

    /**
     * @AuthName 获取角色列表
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGroupList()
    {
        return Db::table('system_group')->field('id,name')->select();
    }

    /**
     * @AuthName 修改用户
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function edit()
    {
        $input = Request::post();
        Db::table('system_aduser')->where('id', $input['id'])->update($input);
        return ['status'=>'success', 'msg'=>'修改用户成功'];
    }

    /**
     * @AuthName 删除用户
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        $input = Request::post();
        Db::table('system_aduser')->where('id', $input['id'])->delete();
        return ['status'=>'success', 'msg'=>'删除用户成功'];
    }

    /**
     * @AuthName 添加用户
     * @return array
     */
    public function add()
    {
        $input = Request::post();
        Db::table('system_aduser')->strict(false)->data($input)->insert();
        return ['status'=>'success', 'msg'=>'添加用户成功'];
    }

    public function changePswd()
    {
        $input = Request::post();
        Db::table('system_aduser')->where('id', $input['uid'])->update(['password'=>md5(md5($input['newpassword']))]);
        return ['status'=>'success', 'msg'=>'修改密码成功'];
    }

}