<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest as Request;
use App\Transformers\DepositTransformer;
use App\Transformers\WithdrawalTransformer;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }

    /**
     * Create deposit of enabled wallet user;
     *
     * @param Request $request
     * @return void
     */
    public function storeDeposit(Request $request)
    {
        $user = auth()->user();

        $wallet = $user->wallet;

        if(is_null($wallet)) {
            throw new \Exception('This customer does not has wallet');
        }

        if($wallet->isDisabled()) {
            throw new \Exception('This customer does not has active wallet');
        }

        $transaction = $wallet->deposits()->create([
            'transaction_actor_id'  => $user->id,
            'status'    => 'success',
            'amount'    => (int) $request->get('amount'),
            'reference_id' => $request->get('reference_id')

        ]);

        return fractal($transaction, new DepositTransformer())->respond(201);
    }

    /**
     * Create withdrawal of enabled wallet user;
     *
     * @param Request $request
     * @return void
     */
    public function storeWithdrawal(Request $request)
    {
        $user = auth()->user();

        $wallet = $user->wallet;

        if(is_null($wallet)) {
            throw new \Exception('This customer does not has wallet');
        }

        if($wallet->isDisabled()) {
            throw new \Exception('This customer does not has active wallet');
        }

        $amount = ((int) $request->get('amount')) * -1;

        if($amount > $wallet->balance) {
            throw new \Exception('The amount must not be more than the current balance');
        }

        $transaction = $wallet->withdrawals()->create([
            'transaction_actor_id'  => $user->id,
            'status'    => 'success',
            'amount'    => $amount,
            'reference_id' => $request->get('reference_id')

        ]);

        return fractal($transaction, new WithdrawalTransformer())->respond(201);
    }
}
