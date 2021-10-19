<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ExceptionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return [
                'error' => 'Endpoint not found, please check again.'

            ];
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return [
                'error' => $exception->errors()
            ];
        }


        return [
            'error' => $exception->getMessage()
        ];
    }
}
