<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/4/2
 * Time: 22:05
 */

namespace app\admin\controller;

use app\common\model\SysConfig;
use app\common\service\Token;
use myutils\StringUtil;
use think\captcha\Captcha;
use think\Db;
use think\facade\Cache;
use think\facade\Request;

class Login
{
    public function getPublicConfig()
    {
        $webSet = (new SysConfig())->getSysConfig('public');
        $webSet['local_session'] = StringUtil::random(16);
        return $webSet;
    }

    public function loginIn()
    {
        $userName = Request::post('username');
        $passWord = Request::post('password');
        $verifyCode = Request::post('verifycode');
        $local_session = Request::post('local_session');
        $res = ['requiredVerify'=>false, 'islogin'=>false];

        if(!$local_session){
            return ['code'=>0, 'msg'=>'请求错误', 'data'=>$res];
        }

        //设置验证码错误次数
        if(!Cache::get('loginerror'.$local_session)){
            Cache::set('loginerror'.$local_session, 0, 600);
        }

        if(Cache::get('loginerror'.$local_session)>=3 && !$this->checkCode($verifyCode)){
            return ['code'=>0, 'msg'=>'验证码错误', 'data'=>$res];
        }

        $aduser = $this->checkAduser($userName, $passWord);

        if(isset($aduser['error'])){
            Cache::inc('loginerror'.$local_session);
            if(Cache::get('loginerror'.$local_session)>=3){
                $res['requiredVerify'] = true;
            }
            return ['code'=>0, 'msg'=>$aduser['error'], 'data'=>$res];
        }

        $res = [
            'uid'   =>$aduser['id'],
            'mobile'=>$aduser['mobile'],
            'usergroup'=>$aduser['role_id'],
            'groupname'=>Db::table('system_group')->where('id', $aduser['role_id'])->value('name'),
            'nickname'=>$aduser['nickname'],
            'islogin'=>true
        ];
        $res['token'] = Token::createAccess($res);
        return ['code'=>0, 'msg'=>$res['groupname'].'登录成功', 'data'=>$res];
    }

    private function checkAduser($userName, $passWord)
    {
        $aduser = Db::table('system_aduser')->where('mobile', $userName)->find();
        if(!$aduser){
            return ['error'=>'账号不存在'];
        }
        if(md5(md5($passWord))!==$aduser['password']){
            return ['error'=>'密码错误'];
        }
        if($aduser['status']==1){
            return ['error'=>'账户已锁定'];
        }
        return $aduser;
    }

    public function GraphicVerificationCode()
    {
        $captcha = new Captcha(['length'=>4]);

        return $captcha->entry(Request::get('local_session'));
    }

    private function checkCode($verifyCode)
    {
        $captcha = new Captcha();
        return $captcha->check($verifyCode);
    }

}