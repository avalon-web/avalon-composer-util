<?php

namespace App\Http\Middleware;

use Closure;

use App\Util\SignatureUtil;

class CheckSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        if(true){
//            return $next($request);
//        }
        $arr = $request->all();

        $signature = SignatureUtil::getSignature($arr);
        //和head中的Authorization进行对比
        $authorization = $request->header('Authorization');
        if ($signature !== $authorization) {
            \Log::info("$signature !== $authorization");
            return \response([], 401);
        }
        return $next($request);
    }
}
