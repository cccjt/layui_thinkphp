<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/8/9
 * Time: 0:36
 */

namespace app\admin\controller\system;


use think\Db;
use think\facade\Request;

class Note
{

    public function getNote()
    {
        return Db::table('system_note')->where('uid', Request::get('uid'))->find();
    }

    public function saveNote()
    {
        $input = Request::post();
        if(Db::table('system_note')->where('uid', $input['uid'])->find()){
            Db::table('system_note')->update(['uid'=>$input['uid'], 'note'=>$input['note']]);
        }else{
            Db::table('system_note')->insert(['uid'=>$input['uid'], 'note'=>$input['note']]);
        }
        return ['status'=>'success', 'msg'=>'更新成功'];
    }
}