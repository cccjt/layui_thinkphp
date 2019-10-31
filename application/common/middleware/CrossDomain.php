<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/4/3
 * Time: 17:14
 */

namespace app\common\middleware;

class CrossDomain
{
    public function handle($request, \Closure $next)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE');
        header('Access-Control-Max-Age: 1728000');

        return $next($request);
    }
}