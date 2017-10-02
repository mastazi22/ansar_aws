<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class CheckAuthentication
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
        if (auth()->guest()) {
            Session::put('redirect_url',$request->url());
            If($request->ajax()){
                return Response::json(['status'=>'logout','loc'=>action('UserController@login')]);
            }
            return redirect()->action('UserController@login');
        }
        $input = $request->input();
        $input['action_user_id'] = auth()->user()->id;
        $request->replace($input);
        return $next($request);
    }
}
