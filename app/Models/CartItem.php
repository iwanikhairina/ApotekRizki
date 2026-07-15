<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'obat_id', 'quantity'];

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public static function countForUser(?int $userId): int
    {
        if (!$userId) {
            return 0;
        }

        return (int) static::where('user_id', $userId)->sum('quantity');
    }
}