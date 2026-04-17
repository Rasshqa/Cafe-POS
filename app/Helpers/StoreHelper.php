<?php

namespace App\Helpers;

use App\Models\Store;

class StoreHelper
{
    public static function current()
    {
        if (!auth()->check()) return null;

        if (auth()->user()->role === 'owner' && session()->has('current_store_id')) {
            return Store::find(session('current_store_id'));
        }

        return Store::find(auth()->user()->store_id);
    }
}
