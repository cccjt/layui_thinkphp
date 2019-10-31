<?php
namespace app\admin\controller;


class Error
{

    public function _empty()
    {
        return ['code'=>404, 'msg'=>'no find', 'data'=>''];
    }

}