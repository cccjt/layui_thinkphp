<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/8/6
 * Time: 1:02
 */

namespace app\common\model;

use ReflectionClass;
use think\Db;
use think\facade\Config;
use think\facade\Env;
use think\facade\Request;
use think\Model;

class SystemRole extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'system_roles';

    public function flushRoles()
    {
        $module_path = Env::get('MODULE_PATH') . 'controller';
        $list = $this->tree($module_path);
        Db::table($this->table)->delete(true);
        foreach ($list as $k => $v) {
            if(!strstr($v, 'system/Role')){
                $classes = str_replace('.php', '', str_replace(Env::get('APP_PATH'), '', $v));
                $classes = str_replace('/', '\\', $classes);
                // $classes = preg_replace('/\\/', '\\\/', $classes);
                $classes = '\app\\'.$classes;
                $this->findFunDoc($classes);
            }
        }
        return ['status'=>'success'];
    }

    private function findFunDoc($classes)
    {
        $model = Request::module();
        $controller = Config::get('url_controller_layer');
        $basePath = "app\\$model\\$controller\\";

        try {
            $classz = new ReflectionClass($classes);
            if($classz->getParentClass() && ($classz->getParentClass())->name!='app\common\controller\AdminBaseController'){
                return;
            }
            $control = str_replace($basePath, '', $classz->getName());
            preg_match('/@AuthName\s+.+\B/', $classz->getDocComment(), $cmatches);
            $data = [];
            if(count($cmatches)>0){
                $data[] = ['action'=>$control, 'controller'=>null, 'title'=>trim(str_replace('@AuthName','',$cmatches[0]))];
                $methods = $classz->getMethods();
                foreach ($methods as $k) {
                    preg_match('/@AuthName\s+.+\B/', $k->getDocComment(), $matches);
                    if(count($matches)>0 && $k->isPublic() && $k->class==$classz->getName()){
                        $action = str_replace($basePath, '', $k->class).'\\'.$k->getName();

                        $data[] = ['action'=>$action, 'controller'=>$control, 'title'=>trim(str_replace('@AuthName','',$matches[0]))];
                    }
                }
                $this->insertAll($data);
            }
        } catch (\ReflectionException $e) {
            return;
        }
    }

    public function tree($directory, &$files=array())
    {
        $mydir = dir($directory);
        $path = $directory;
        while ($file = $mydir->read()) {
            if($file != "." && $file != ".."){
                if (is_dir("$directory/$file")) {
                    $path = "$directory/$file/";
                    $this->tree("$directory/$file", $files);
                } else {
                    $files[] = $path.'/'.$file;
                }
            }
        }
        $mydir->close();
        return $files;
    }

}