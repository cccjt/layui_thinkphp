<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/4/2
 * Time: 23:52
 */

namespace app\admin\controller\system;

use app\common\model\SysConfig;
use think\facade\Request;

/**
 *
 * @AuthName 系统配置
 * DataTime 2019/8/6 1:35
 * @package app\admin\controller\system
 */
class Configuration extends \app\common\controller\AdminBaseController
{

    /**
     * @AuthName 系统配置
     * @return array
     */
    public function index()
    {
        return SysConfig::column('value', 'name');
    }

    /**
     * @AuthName 修改系统设置
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function setConfig()
    {
        $data = Request::post();
        foreach ($data as $k => $v) {
            SysConfig::where('name', $k)->update(['value'=>$v]);
        }

        return ['msg'=>'更新成功', 'status'=>'success'];
    }

    /**
     * @AuthName 上传Logo
     * @return array
     */
    public function upLoadLogo()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = Request::file('image');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move('uploads');
        if($info){
            $url = Request::domain().'/uploads/'.$info->getSaveName();
            return ['img_url'=>$url,'file_name'=>$info->getSaveName(), 'status'=>0];
        }else{
            return ['msg'=>$info->getError()];
        }
    }

}