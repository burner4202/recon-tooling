<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Log;
use Auth;
use Carbon\Carbon;

use Vanguard\RouteLogging;

class LogRouteMiddleware
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


    	if(Auth()->user()) {

    		$url = $request->fullUrl();
    		$ip = $request->ip();
    		$username = Auth()->user()->username;
            $time = Carbon::now();

            # Exclude API Calls
            if (preg_match('/\bapi\b/', $url)) {

                # Don't want API calls, Ignore


            } elseif (preg_match('/\bautocomplete\b/', $url)) {

                # Don't want autocompletes, Ignore

            } else {

                # Save
                
              $route = new RouteLogging();
              $route->user_id = Auth::id();
              $route->username = $username;
              $route->ip = $ip;
              $route->url = $url;
              $route->save();
          }


      }

      return $next($request);
  }
}
