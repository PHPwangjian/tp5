<?php
/**
 * Created by PhpStorm.
 * User: BD_NET
 * Date: 2019/12/6
 * Time: 17:41
*/
namespace app\http\middleware;
use think\facade\Request;
use think\facade\Cookie;
class CheckToken
{
    public function handle($request, \Closure $next)
    {
        $userid =Cookie::get('id');
        if(empty($userid)){
            return redirect('/index');
        }
        else{
            $request->userid=$userid;
        }
        return $next($request);
    }






}

