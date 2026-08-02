<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // 
        });

        // Menangani ModelNotFoundException
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Resource tidak ditemukan',
                    'error' => $e->getMessage()
                ], 404);
            }

            // Redirect ke halaman 404 custom
            return redirect()->route('error.404')->with('error', 'Data yang Anda cari tidak ditemukan');
        });

        // Menangani NotFoundHttpException
        $this->renderable(function (NotFoundHttpException $e, $request) {
            
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Halaman atau resource tidak ditemukan',
                    'error' => $e->getMessage()
                ], 404);
            }

            // Redirect ke halaman 404 custom
            return response()->view('errors.404', [
                'message' => 'Halaman tidak ditemukan'
            ], 404);
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Menangani ModelNotFoundException secara spesifik
        if ($exception instanceof ModelNotFoundException) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Data tidak ditemukan',
                    'model' => class_basename($exception->getModel())
                ], 404);
            }

            return redirect()->back()->with('error', 'Data yang Anda cari tidak ditemukan!');
        }

        return parent::render($request, $exception);
    }
}