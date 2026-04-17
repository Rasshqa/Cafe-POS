<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use \App\Traits\BelongsToStore;

    protected $fillable = ['total_amount', 'discount', 'tax', 'pay_amount', 'return_amount', 'payment_method'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
