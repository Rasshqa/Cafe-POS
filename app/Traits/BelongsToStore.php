<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToStore
{
    protected static function bootBelongsToStore()
    {
        static::addGlobalScope('store', function (Builder $builder) {
            if (auth()->check()) {
                if (auth()->user()->role === 'owner') {
                    // Owner melihat semua cabang atau difilter lewat session
                    if (session()->has('current_store_id')) {
                        $builder->where('store_id', session('current_store_id'));
                    }
                } else {
                    // Kasir dan Admin cabang otomatis di-filter ke cabang mereka
                    $builder->where('store_id', auth()->user()->store_id);
                }
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && !$model->store_id) {
                if (auth()->user()->role === 'owner' && session()->has('current_store_id')) {
                    $model->store_id = session('current_store_id');
                } elseif (auth()->user()->store_id) {
                    $model->store_id = auth()->user()->store_id;
                }
            }
        });
    }

    public function store()
    {
        return $this->belongsTo(\App\Models\Store::class);
    }
}
