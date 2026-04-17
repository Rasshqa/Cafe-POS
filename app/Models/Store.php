<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'logo', 'default_tax'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
