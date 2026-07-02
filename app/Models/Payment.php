<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Scopes\TenantScope;

#[Fillable(['homestay_id', 'reservation_id', 'amount', 'payment_method', 'payment_status', 'payment_date'])]
class Payment extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
        ];
    }

    public function homestay()
    {
        return $this->belongsTo(Homestay::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
