<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

abstract class TransactionTransformer extends TransformerAbstract
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
     * Transaction|Deposit|Withdrawal $transaction
     *
     * @return array
     */
    public function transform($transaction)
    {
        return [
            'id' => $transaction->id,
            'status' => $transaction->status,
            'reference_id' => $transaction->reference_id,
            'amount' => $transaction->amount
        ];
    }
}
