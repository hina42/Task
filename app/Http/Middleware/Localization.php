<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(! in_array($request->segment(1),config("app.available_locales"))){
          // dd($request->segment(1));
           abort(400);
        }
       //dd($request->segment(1));
       $locale = $request->segment(1);
       $_COOKIE['language'] = $locale ;
        \App::setlocale($locale);
        return $next($request);
    }
}
