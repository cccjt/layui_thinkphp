<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/8/12
 * Time: 22:11
 */

namespace app\admin\controller\system;


use app\common\controller\AdminBaseController;
use think\Db;
use think\facade\Request;

class Menu extends AdminBaseController
{

    public function index()
    {
        $res = [];
        $where = Request::has('parent')?['parent'=>Request::post('parent')]:['parent'=>null];
        $where = Request::has('id')?['id'=>Request::post('id')]:$where;

        if(Request::has('id')){
            $res = Db::table('system_menu')->where($where)->find();
            $res['parentname'] = Db::table('system_menu')->where('id', $res['parent'])->order('sort asc')->value('title');
        }else{
            $res['rows'] = Db::table('system_menu')->where($where)->order('sort asc')->select();
        }
        return $res;
    }

    public function getRoleList()
    {
        $res = Db::table('system_roles')->where('controller', 'not null')->select();
        return $res;
    }

    public function add()
    {
        $input = Request::post();
        return Db::table('system_menu')->data($input)->insert();
    }

    public function edit()
    {
        $input = Request::post();
        if($input['action']=='add'){
            return Db::table('system_menu')->data($input)->insert();
        }
        if($input['action']=='edit'){
            return Db::table('system_menu')->update($input);
        }
        if($input['action']=='del'){
            Db::table('system_menu')->where('id', $input['id'])->delete();
            return ['status'=>'success', 'msg'=>'删除成功!'];
        }
    }

    public function getLeftMenu()
    {
        $res = Db::table('system_menu')->where('parent', null)->order('sort asc')->select();
        foreach ($res as $k => $v) {
            $list = Db::table('system_menu')->where('parent', $v['id'])->order('sort asc')->select();
            if(count($list)>0){
                $res[$k]['list'] = $list;
            }else{
                if(!$v['jump']) unset($res[$k]);
            }

        }
        return $res;
    }

}