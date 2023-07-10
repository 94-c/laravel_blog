<?php

namespace App\Filter;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CacheFilter
{
    public function grab(Route $route, Request $request)
    {
        $key = $this->keygen($request->url());

        if (Cache::has($key)) return Cache::get($key);
    }

    public function set(Route $route, Request $request, Response $response)
    {
        $key = $this->keygen($request->url());

        if (!Cache::has($key)) Cache::put($key, $response->getContent(), 1);
    }

    protected function keygen($url)
    {
        return 'route_' . Str::slug($url);
    }

}
