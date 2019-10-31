<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/8/5
 * Time: 17:40
 */

namespace app\admin\controller\system;

use app\common\controller\AdminBaseController;
use app\common\model\SystemRole;
use think\Db;
use think\facade\Request;

/**
 * @AuthName 系统用户组设置
 * Class Group
 * DataTime 2019/8/7 23:14
 * @package app\admin\controller\system
 */
class Group extends AdminBaseController
{
    /**
     * @AuthName 系统用户组
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $table = Db::table('system_group');
        $input = Request::post();

        $res['rows'] = $table->limit($input['limit'])->page($input['page'])->order($input['field'], $input['order'])->select();

        $res['total'] = Db::table('system_group')->count();
        return $res;
    }

    /**
     * @AuthName 获取权限表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRoles()
    {
        (new SystemRole())->flushRoles();
        $controller = Db::table('system_roles')->where('controller', 'null')->select();
        foreach ($controller as $k => $v) {
            $data[$k]['controller'] = $v;
            $data[$k]['action'] = Db::table('system_roles')
                ->where('controller', $v['action'])
                ->where('controller', 'not null')
                ->select();
        }
        return $data;
    }

    /**
     * @AuthName 修改用户组
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editGroup()
    {
        $table = Db::table('system_group');

        if(Request::isGet() && Request::get('id')!='undefined'){
            return $table->where('id', Request::get('id'))->find();
        }

        if(Request::isPost()){
            $input = Request::post();

            if($input['action']=='add'){ //添加
                if(Db::table('system_group')->where('name', $input['name'])->find()){
                    return ['msg'=>'用户组已存在!', 'status'=>'error'];
                }
                if(isset($input['roles'])){
                    $input['roles'] = json_encode($input['roles']);
                    $input['titles'] = json_encode($input['titles']);
                }
                unset($input['action']);
                $table->insert($input);
                return ['msg'=>'添加用户组成功!'];
            }

            if($input['action']=='del' && $input['id']){ //删除
                $table->where('id', $input['id'])->delete();
                return ['msg'=>'删除用户组成功!'];
            }

            if($input['action']=='edit' && $input['id']){ //修改
                if(isset($input['roles'])){
                    $input['roles'] = json_encode($input['roles']);
                    $input['titles'] = json_encode($input['titles']);
                }
                unset($input['action']);
                $table->where('id', $input['id'])->update($input);
                return ['msg'=>'修改用户组成功!'];
            }
        }
        return ['status'=>'error'];
    }
}