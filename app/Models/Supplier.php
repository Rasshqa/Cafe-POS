<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use \App\Traits\BelongsToStore;

    protected $fillable = ['name', 'contact', 'address'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
