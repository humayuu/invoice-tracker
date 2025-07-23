<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    // app/Models/Invoice.php

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'invoice_payment')
            ->withPivot('amount_applied')
            ->withTimestamps();
    }

    public function getAmountPaidAttribute()
    {
        return $this->payments->sum('pivot.amount_applied');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->amount_paid;
    }
}
