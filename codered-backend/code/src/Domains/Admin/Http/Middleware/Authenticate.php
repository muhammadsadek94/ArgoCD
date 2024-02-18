<?php

namespace App\Domains\Admin\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Constants;

class Authenticate
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->auth = auth()->guard('admin');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected $timeout = 30; // 30 minutes
    public function handle($request, Closure $next)
    {


        if (! session()->has('lastActivityTime')) {
            session(['lastActivityTime' => now()]);
        }

        if ($this->auth->check())
        {
            // dd(session('agent_device')!= $request->ip() . $request->server('HTTP_USER_AGENT'));
            if(session('agent_device')!= $request->ip() . $request->server('HTTP_USER_AGENT'))
            {
                Session()->forget('agent_device');
                session(['agent_device' => $request->ip() . $request->server('HTTP_USER_AGENT')]);
                return $this->redirect(false,$request, $next);
            }
            $lastActivity = session('last_activity');
            if (!is_null($lastActivity) && Carbon::now()->diffInMinutes($lastActivity) >= $this->timeout) {
                Auth::logout();
                session()->flush();
                return redirect(route('admin.login'))->with('message', 'You have been logged out due to inactivity.');
            }
            session(['last_activity' => Carbon::now()]);
           return $this->redirect($this->checkIfHasAccess(),$request,$next);
        }
        return $this->redirect(false,$request, $next);
    }


    public function redirect($response, $request, Closure $next)
    {
        if (!$response) {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect()->guest(Constants::ADMIN_BASE_URL.'/login');
            }
        }
        return $next($request);
    }

    public function checkIfHasAccess()
    {
        return true;
        $activation = $this->auth->user()->activation;
        if ($activation) {
            return true;
        }
        $this->auth->logout();
        return redirect()->guest(Constants::ADMIN_BASE_URL.'/login')->withFailed(trans('lang.Your account Is disable !'));
    }

}
