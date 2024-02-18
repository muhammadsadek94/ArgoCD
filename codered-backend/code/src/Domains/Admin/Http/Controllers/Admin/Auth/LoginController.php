<?php

namespace App\Domains\Admin\Http\Controllers\Admin\Auth;

use App\Domains\Admin\Http\Requests\LoginRequest;
use App\Domains\Admin\Models\Admin2fToken;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use INTCore\OneARTFoundation\Http\Controller;
use Constants;
use App\Domains\Admin\Traits\Auth\RedirectsUsers;
use App\Domains\Admin\Traits\Auth\ThrottlesLogins;
use Lang;
use App\Domains\Admin\Models\Admin;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthGaurd, RedirectsUsers, ThrottlesLogins;


    private $redirectTo = Constants::ADMIN_BASE_URL . "/dashboard";

    function __construct()
    {
        $this->middleware("admin.guest", ["except" => ["logout", 'dashboard']]);
    }

    public function dashboard()
    {
        return view("admin::admin.dashboard");
    }

    public function showLoginForm()
    {
        return view("admin::admin.auth.login-form");
    }


    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        //retrieveByCredentials
        $admin = Admin::where(['email' => $request->get('email'), 'activation' => 1])->first();
        if ($admin && Hash::check($request->password, $admin->password)) {

            $token = Admin2fToken::create([
                'admin_id' => $admin->id
            ]);

            if ($token->sendCode()) {
                session()->put("token_id", $token->id);
                session()->put("admin_id", $admin->id);
                session()->put("remember", $request->get('remember'));

                return redirect(route('admin.code_page'));
            }

            $token->delete();// delete token because it can't be sent
            return back()->withErrors([
                $this->username() => "Unable to send verification code"
            ]);
        }

        return redirect()->back()
            ->withInputs($request->all())
            ->withErrors([
                $this->username() => Lang::get('auth.failed'),
            ]);
    }




    /**
     * Show second factor form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showCodeForm()
    {
        if (! session()->has("token_id")) {
            return redirect(route("admin.login"));
        }

        return view("admin::admin.auth.code");
    }

    /**
     * Store and verify user second factor.
     */
    public function storeCodeForm(Request $request)
    {
        // throttle for too many attempts
        if (! session()->has("token_id", "admin_id")) {
            return redirect(route('admin.login'));
        }
        $this->validate(request(), [
            'code' => 'required|array|size:4',
            'code.*' => 'required|integer',
        ], $this->messages());

        $code = implode('', $request->code);

        $request->merge(['code' => $code]);

        $token = Admin2fToken::find(session()->get("token_id")->toString());
        if (! $token ||
        ! $token->isValid() ||
        $request->code != $token->code ||
        session()->get("admin_id") !== $token->admin->id||$token?->created_at <= Carbon::now()->subMinutes(10)
        ) {
            if ($token) {
                if ($token?->created_at <= Carbon::now()->subMinutes(10)) {
                    $token->delete();
                    return redirect(route('admin.login'))->withErrors(['code'=>"Expired token"]);
                }
                $token->try_count = $token->try_count+1;
                $token->save();
                if ($token->try_count >= 3) {
                    $token->delete();
                    return redirect(route('admin.login'))->withErrors(['code'=>"Invalid token"]);
                }
                // return redirect(route('admin.login'));
            }else {
                return redirect(route('admin.login'))->withErrors(['code'=>"Invalid token"]);
            }
            return redirect(route('admin.code_page'))->withErrors(['code'=>"Invalid token"]);
        }
        // dd($token->admin->password);
        // $this->guard()->logoutOtherDevices($token->admin->password);
        // Auth::setUser($token->admin)->logoutOtherDevices($token->admin->password);
        session(['agent_device' => $request->ip() . $request->server('HTTP_USER_AGENT')]);
        $token->used = true;
        $token->save();
        $this->guard()->login($token->admin, session()->get('remember', false));
        $token->admin->last_token = session('_token');
        // dd($token->admin);
        $token->admin->save();
        session()->forget('token_id', 'admin_id', 'remember');

        return redirect($this->redirectTo);
    }


    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|email',
            'password'        => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // fire any events here
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect(Constants::ADMIN_BASE_URL . '/login');
    }

    /**
     * The user has logged out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        // could be fire any events here
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if(method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    public function messages()
    {
        return [
            'code.*.integer' => 'Code must be integer',
            'code.*.required' => 'Code is required',
        ];
    }
}
