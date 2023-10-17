<?php

namespace Modules\Authentication\Services;

use App\Http\Configuration\Logger;
use App\Models\User;
use Carbon\Carbon;
use Core\Services\Service;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Authentication\Contracts\AuthenticationRepository;
use Modules\Authentication\Contracts\AuthenticationService as AuthenticationServiceContract;
use Laravel\Passport\Passport;
use Modules\Authentication\Http\Requests\LoginValidationRequest;
use Throwable;

class AuthenticationService extends Service implements AuthenticationServiceContract
{
    use AuthenticatesUsers;
    /**
     * Create new instance of DeveloperService
     *
     * @param AuthenticationRepository $repository
     * @return void
     */
    public function __construct(AuthenticationRepository $repository)
    {
        parent::__construct($repository);
    }

    public function login(LoginValidationRequest $request)
    {

        $statusCode = 200;
        $responseData = null;
        $error = null;
        $status = false;
        $responseMessage = '';

        try {
            $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? "email" : "username";

            $request->merge([
                $loginType => $request->login
            ]);
            $credentials = request([$loginType, 'password']);
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                $responseMessage = $this->sendLockoutResponse($request);
                $statusCode = Response::HTTP_TOO_MANY_REQUESTS;
            } else {
                if (!Auth::attempt($credentials)) {
                    $responseMessage = "Invalid Credentials";
                    $this->incrementLoginAttempts($request);
                    $statusCode = 400;
                } else {
                    $user = $request->user();
                    if ($request->remember_me) {
                        Passport::personalAccessTokensExpireIn(Carbon::now()->addWeeks(config('token.remember_token_expires_in')));
                    }
                    $tokenResult = $user->createToken(config("token.secret"));
                    $token = $tokenResult->token;
                    $token->save();
                    $user->save();
                    $user->application;
                    $responseData = [
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                        'user' => $user
                    ];
                    $responseMessage = 'Login successful';
                    $status = true;
                    $this->clearLoginAttempts($request);
                }
            }
        } catch (Throwable $e) {
            $responseMessage = "An error occurred";
            $error =  Logger::errorLog($e);
            $statusCode = 500;
        }
        return response()->json([
            "data" => $responseData,
            "status" => $status,
            "message" => $responseMessage,
            "error" => $error
        ], $statusCode);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     *
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        return 'Maximum auth attempts exceeded. Please try again after ' . secondsToTime($seconds);
    }
}
