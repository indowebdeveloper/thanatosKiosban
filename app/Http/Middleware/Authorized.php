<?php
namespace Thanatos\Http\Middleware;

use Auth;
use Closure;

class Authorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $params)
    {
      if (!Auth::user()->can($params) && !Auth::user()->can('god-mode')) {
            return response()->json(array(
                'message' => 'forbidden request'
              ), 403);
            die();
        }
      return $next($request);
    }
}
