<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class DepositTransformer extends TransactionTransformer
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($deposit)
    {
        $data = parent::transform($deposit);

        return [
            'deposit' => array_merge($data, [
                'deposited_by' => $deposit->transaction_actor_id,
                'deposited_at' => $deposit->created_at
            ])
        ];
    }
}
