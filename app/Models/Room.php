<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Scopes\TenantScope;

#[Fillable(['homestay_id', 'room_number', 'room_type', 'price_per_night', 'status', 'description'])]
class Room extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
