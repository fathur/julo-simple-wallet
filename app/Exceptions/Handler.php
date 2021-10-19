<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Transformers\ExceptionTransformer;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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

        $this->renderRenderable();
        
    }

    protected function fractalException($e, $statusCode = 400)
    {
        return fractal($e, new ExceptionTransformer())
            ->addMeta([
                'status' => 'fail'
            ])
            ->respond($statusCode);
    }

    protected function renderRenderable() {

        $this->renderable(function(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return $this->fractalException($e, 404);
        });

        $this->renderable(function(\Illuminate\Validation\ValidationException $e, $request) {
            return $this->fractalException($e);
        });

        $this->renderable(function(\Exception $e, $request) {
            return $this->fractalException($e);
        });
    }
}
