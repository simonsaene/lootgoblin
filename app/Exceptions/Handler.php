<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PDOException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, \Throwable $exception)
    {
        // 404 Not Found Error
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', ['message' => 'The page you are looking for could not be found.'], 404);
        }
    
        // 419 TokenMismatchError (Session expired)
        if ($exception instanceof \Illuminate\Session\TokenMismatchException && Auth::guest()) {
            Log::error('Session expired error: ' . $exception->getMessage());
            return response()->view('errors.token-mismatch', ['message' => 'Session expired. Please refresh the page and try again.'], 419);
        }
    
        // 405 Method Not Allowed
        if ($exception instanceof MethodNotAllowedHttpException) {
            Log::error('Method not allowed error: ' . $exception->getMessage());
            return response()->view('errors.method-not-allowed', ['message' => 'The requested method is not allowed.'], 405);
        }
    
        // 500 Database Error (PDOException or QueryException)
        if ($exception instanceof PDOException || $exception instanceof QueryException) {
            Log::error('Database Error: ' . $exception->getMessage());
            return response()->view('errors.database', ['message' => 'A database error occurred. Please try again later.'], 500);
        }
    
        // Handle other errors
        return parent::render($request, $exception);
    }
    
}
