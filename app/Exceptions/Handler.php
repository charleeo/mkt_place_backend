<?php

namespace App\Exceptions;

use App\Http\Configuration\AppHttpUtils;
use App\Http\Configuration\Logger;
use App\Http\Configuration\ValidationUtils;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // $this->unauthenticated();
        });
    }



    public function render($request, Throwable $exception)
    {
        // handle validation exception
        if ($exception instanceof ValidationException) {
            return $this->handleValidationException($exception, $request);
        }

        // handle all 404
        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            // package the response data
            $res = AppHttpUtils::responseStructure("404, Not Found!", false, null);
            // write to log
            write_log(Logger::getLogData($request, $res, '404'));
            // return response
            return response()->json($res, Response::HTTP_OK);
        }

        // handle all AuthorizationException
        elseif ($exception instanceof AuthorizationException && $request->wantsJson()) {
            // package the response data
            $res = AppHttpUtils::responseStructure($exception->getMessage() ?? "Access Denied! You don't have the right permissions to do that", false, null);
            // write to log
            write_log(Logger::getLogData($request, $res, 'access denied'));
            // return response
            return response()->json($res, Response::HTTP_OK);
        }

        return parent::render($request, $exception);
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {

            $res = [
                'status' => false,
                'response' => null,
                'message' => 'Valid auth credentials required',
                'code' => 401,
                'authenticated' => false,
            ];

            // write to log
            write_log(Logger::getLogData($request, $res, 'authentication failed'));

            return response()->json($res, 200);
        }

        return redirect()->guest('login');
    }

    /**
     * ValidationException
     * Parameters did not pass validation
     *
     * @param ValidationException $exception
     * @return \Illuminate\Http\Response 422 with custom response structure
     */
    protected function handleValidationException(ValidationException $exception, Request $request)
    {
        $validationUtils = new ValidationUtils();
        return $validationUtils->validationErrorResponse($exception, $request);
    }
}
