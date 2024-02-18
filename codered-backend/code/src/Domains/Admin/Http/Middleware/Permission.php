<?php

namespace App\Domains\Admin\Http\Middleware;


use Closure;
use Auth;
use App\Models\Development\Permission\ProtectedUrl;
class Permission {

	protected $auth ;

	function __construct()
	{
        $this->auth = auth()->guard('admin')->user();
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		return $next($request);
		// TODO: Update Permission System
//		$currentAction = str_replace("App\Http\Controllers\\", '', "".$request->route()->getAction()['controller']);
//		// dd($currentAction);
//		$expect = ProtectedUrl::Exceptions()->pluck('action')->toArray();
//		$routes_list = array_merge($this->auth->urls->toArray(),$expect);
//		if (in_array($currentAction,$routes_list)) {
//			return $next($request);
//		}
//		else{
//			if ($request->ajax()) {
//				return response()->json(array('status'=>'false','message'=>'ليس لديك صلاحيات'));
//				// return response('Unauthorized.', 403);
//			}else{
//				return abort(403);
//			}
//		}
	}




}
