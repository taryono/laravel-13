<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Method 1: Report all exceptions with detailed log
        $this->reportable(function (Throwable $e) {
            Log::channel('stack')->error('EXCEPTION CAUGHT: ' . $e->getMessage(), [
                'class' => get_class($e),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_url' => request()->fullUrl(),
                'request_method' => request()->method(),
                'request_input' => request()->all(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        });

        // Method 2: Specific logging for 404
        $this->reportable(function (NotFoundHttpException $e) {
            Log::channel('stack')->warning('404 NOT FOUND: ' . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'referer' => request()->header('referer'),
                'user_agent' => request()->userAgent(),
            ]);
        });

        // Method 3: Specific logging for Model Not Found
        $this->reportable(function (ModelNotFoundException $e) {
            Log::channel('stack')->error('MODEL NOT FOUND: ' . $e->getMessage(), [
                'model' => $e->getModel(),
                'ids' => $e->getIds(),
            ]);
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Optional: Log before rendering
        Log::channel('stack')->debug('Rendering exception: ' . get_class($exception));
        
        return parent::render($request, $exception);
    }
}