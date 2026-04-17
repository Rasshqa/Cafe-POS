<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['total_amount', 'pay_amount', 'return_amount'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
