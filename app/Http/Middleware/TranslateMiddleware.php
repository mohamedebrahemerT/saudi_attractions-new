<?php

namespace App\Http\Middleware;

use App\Models\Locale;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;

class TranslateMiddleware
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
        if(Request::get('lang'))
        {
        $lang=Locale::where('code',Request::get('lang'))->first();
        if($lang) {
            App::setLocale($lang->code);
            return $next($request);
        }
    }
        $lang=Locale::where('default',0)->first()->code;
        App::setLocale($lang);
        return $next($request);
    }
}
