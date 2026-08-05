<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
            //
        });

        // Normalize validation failures into the API error envelope expected
        // by the Flutter client: { success:false, error:{code,message,details} }.
        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'REPORT_002',
                        'message' => 'Data laporan tidak valid.',
                        'details' => $e->errors(),
                    ],
                ], $e->status);
            }
        });
    }

    /**
     * Convert an authentication exception into a 401 JSON response.
     *
     * This is a pure API backend with no `login` web route, so the default
     * redirect-to-`login` behaviour must be replaced with a JSON 401.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => 'AUTH_003',
                'message' => 'Tidak terautentikasi.',
            ],
        ], 401);
    }
}
