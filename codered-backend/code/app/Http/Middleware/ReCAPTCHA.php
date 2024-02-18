<?php

namespace Framework\Http\Middleware;

use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Log;
class ReCAPTCHA extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param null $guard
     * @return string
     */
    public function handle($request, \Closure $next, ...$guard)
    {
        return $next($request);

        $base_url = config('services.Recaptcha.url');
        $token = $request->token;

        $secret = config('services.Recaptcha.secret');
        $data = [
            'secret' => $secret,
            'response' => $token
        ];


        $response = Http::asForm()->post($base_url, $data );
        $recaotcha_response = json_decode($response->body());
        if(($recaotcha_response->success && $recaotcha_response->score < .5) || !$recaotcha_response->success ){
            Log::info('user marked as spam. ip:'. $request->ip());
                return response()->json([
                    "errors"=>[
                        [
                            "name"=> "server",
                            "message"=> "server not responding."
                    ],
                    ]
                ], 422);
        }

        return $next($request);
    }
}
