<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $domain = $request->getHost();
        $url = preg_replace('(^https?://)', '', url()->current());

        \Log::debug("[System] Check Unauthenticated Handler Method ~ App\Exceptions\Handler@unauthenticated", [
            'domain' => $domain,
            'url' => $url,
            'request' => [
                'route' => $request->route()->computedMiddleware
            ]
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        } else {
            if (strpos($url, '!') !== false || in_array('auth:admin', $request->route()->computedMiddleware)) {
                return redirect()->route('adm.login');
            } elseif (strpos($url, 's') !== false) {
                return redirect()->route('login');
            }
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
