<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Withdrawal extends Transaction
{
    protected $table = 'transactions';

    protected static function booted()
    {
        parent::booted();
        
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('amount', '<', 0);
        });
    }
}
