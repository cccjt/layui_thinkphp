<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/4/3
 * Time: 18:51
 */

namespace app\common\middleware;


class JsonReturn
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        if($response->getHeader()['Content-Type']=="application/json; charset=utf-8"){
            $data = $response->getData();

            $Rdata = isset($data['data'])?$data['data']:$data;
            $Rcode = isset($data['code'])?$data['code']:0;
            $Rmsg = isset($data['msg'])?$data['msg']:'';
            $status = isset($data['status'])?$data['status']:'success';
            if(isset($Rdata['msg'])){
                unset($Rdata['msg']);
            }
            if(isset($Rdata['code'])) unset($Rdata['code']);
            if(isset($Rdata['status'])) unset($Rdata['status']);


            $response = $response->data(['code'=>$Rcode, 'msg'=>$Rmsg, 'status'=>$status, 'data'=>$Rdata]);
        }

        // 添加中间件执行代码
        return $response;
    }
}