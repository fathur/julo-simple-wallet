<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Wallet extends Model
{
    use HasFactory, Uuid;


    protected $fillable = ['enabled_at', 'disabled_at', 'balance'];

    protected $casts = [
        'enabled_at' => 'datetime',
        'disabled_at' => 'datetime',
        'balance' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'wallet_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'wallet_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }

    public function status()
    {
        $enabledAt = $this->enabled_at;
        $disabledAt = $this->disabled_at;

        if(is_null($enabledAt) && is_null($disabledAt)) {
            return 'disabled';
        }

        if ($enabledAt && is_null($disabledAt)) {
            return 'enabled';
        }

        if ($disabledAt && is_null($enabledAt)) {
            return 'disabled';
        }

        if($enabledAt->gt($disabledAt)) {
            return 'enabled';
        }

        if($disabledAt->gt($enabledAt)) {
            return 'disabled';
        }
    }

    public function isEnabled()
    {
        return $this->status() == 'enabled';
    }

    public function isDisabled()
    {
        return $this->status() == 'disabled';
    }
}
