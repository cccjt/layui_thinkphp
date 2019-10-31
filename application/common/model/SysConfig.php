<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/4/2
 * Time: 22:00
 */

namespace app\common\model;

class SysConfig extends BaseModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'system_config';

    public function getSysConfig($group='public')
    {
        $res = $this->where(['group'=>$group])->column('value', 'name');
        return $res;
    }

    public function getAllConfig()
    {
        $res = $this->select();
        return $res;
    }

    public static function getConf(string $key=null)
    {
        return 'hahahah';
    }
}