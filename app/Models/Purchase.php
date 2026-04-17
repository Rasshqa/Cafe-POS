<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use \App\Traits\BelongsToStore;

    protected $fillable = ['supplier_id', 'total_amount', 'purchase_date', 'notes'];

    protected function casts(): array
    {
        return ['purchase_date' => 'date'];
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
