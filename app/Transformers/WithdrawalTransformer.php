<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class WithdrawalTransformer extends TransactionTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($withdrawal)
    {
        $data = parent::transform($withdrawal);

        return [
            'withdrawal' => array_merge($data, [
                'withdrawan_by' => $withdrawal->transaction_actor_id,
                'withdrawan_at' => $withdrawal->created_at,
                'amount' => abs($data['amount'])
            ])
        ];
    }
}
