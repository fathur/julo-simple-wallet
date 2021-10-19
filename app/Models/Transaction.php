<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use function Illuminate\Events\queueable;

class Transaction extends Model
{
    use HasFactory, Uuid;

    protected $fillable = ['transaction_actor_id', 'amount', 'reference_id', 'status'];

    protected $casts = [
        'amount' => 'integer',
    ];

    protected static function booted()
    {
        /**
         * Update the balance after transaction (deposit or withdrawal).
         * This process using queue, make sure your queue already setup.
         */
        static::created(queueable(function ($transaction) {

            /* Simple balance calculateion */
            // $wallet = $transaction->wallet;
            // $wallet->balance = $wallet->balance + $transaction->amount;
            // $wallet->save();

            /* Stricted balance calculation, take more process to calculate */
            $wallet = $transaction->wallet;
            $balance = $wallet->transactions()->sum('amount');
            $wallet->balance = $balance;
            $wallet->save();
        }));
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}
