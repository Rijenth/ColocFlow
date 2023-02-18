<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
            //
        });
    }

    // this application is an api, all errors are returned in json format

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'status' => 422,
                'detail' => "One or more fields are invalid",
                'errors' => $exception->errors()
            ], 422);
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException || $exception instanceof \Illuminate\Session\TokenMismatchException) {
            return response()->json([
                "status" => 401,
                "detail" => "Unauthenticated",
            ], 401);
        }

        if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return response()->json([
                "status" => 403,
                "detail" => "Forbidden",
            ], 403);
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                "status" => 404,
                "detail" => "Not Found",
                'error' => $exception->getMessage()
            ], 404);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->json([
                "status" => 404,
                "detail" => "Not Found",
                'error' => $exception->getMessage()
            ], 404);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return response()->json([
                "status" => 405,
                "detail" => "Method Not Allowed",
                'error' => $exception->getMessage()
            ], 405);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
            return response()->json([
                "status" => 429,
                "detail" => "Too Many Requests",
                'error' => $exception->getMessage()
            ], 429);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return response()->json([
                "status" => $exception->getStatusCode(),
                "detail" => $exception->getMessage(),
                'error' => $exception->getMessage()
            ], $exception->getStatusCode());
        }

        return response()->json([
            "status" => 500,
            "detail" => "Internal Server Error",
            'error' => $exception->getMessage()
        ], 500);
    }
}
