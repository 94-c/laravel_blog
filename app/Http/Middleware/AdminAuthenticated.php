<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenCustomException;
use Closure;
use Illuminate\Support\Facades\Gate;

class AdminAuthenticated
{
    /**
     * @throws ForbiddenCustomException
     */
    public function handle($request, Closure $next)
    {
        if (!Gate::allows('admin')) {
            throw new ForbiddenCustomException();
        }

        return $next($request);
    }
}
