<?php

namespace TomatoPHP\TomatoPHP\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class LanguageSwitcher extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if(isset($_COOKIE['lang'])){
            $lang = json_decode($_COOKIE['lang']);
            app()->setLocale($lang->id);
        }
        else {
            $_COOKIE['lang'] = json_encode([
                'id' => config('app.locale'),
                'name' => config('app.locale') === 'en' ? 'English' : 'Arabic'
            ]);
        }

        return $next($request);
    }
}
