<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Scopes\TenantScope;

#[Fillable(['homestay_id', 'name', 'email', 'phone', 'identity_number', 'ktp_photo_path'])]
class Guest extends Model
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
