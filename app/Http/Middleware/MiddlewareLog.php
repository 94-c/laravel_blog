<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class MiddlewareLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        //app() : 사용 가능한 컨테이너 인스턴스를 가져옵니다.
        //environment : 현재 애플리케이션 환경을 가져오거나 확인합니다.
        if (app()->environment('local')) {
            $log=[
                'URI' => $request->getUri(),
                'METHOD' => $request->getMethod(),
                'REQUEST_BODY' => $request->all(),
                'RESPONSE' => $request->getContent(),
            ];

            Log::info(json_encode($log));
        }
        return $response;
    }
}
