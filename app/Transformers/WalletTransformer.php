<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Wallet;

class WalletTransformer extends TransformerAbstract
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
    public function transform(Wallet $wallet)
    {
        $data = [
            'id' => $wallet->id,
            'owned_by' => $wallet->owner_id,
            'status' => $wallet->status(),
            'enabled_at' => $wallet->enabled_at,
            'disabled_at' => $wallet->disabled_at,
            'balance' => $wallet->balance ?? 0
        ];

        if ($wallet->isEnabled()) {
            unset($data['disabled_at']);
        } elseif ($wallet->isDisabled()) {
            unset($data['enabled_at']);
        }

        return [
            'wallet' => $data
        ];
    }
}
