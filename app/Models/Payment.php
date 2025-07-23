<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'supplier_id',
        'amount',
        'note',
        'hidden',
    ];

    protected $casts = [
        'hidden' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_payment')
            ->withPivot('amount_applied')
            ->withTimestamps();
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'purchase_payment')
            ->withPivot('amount_applied')
            ->withTimestamps();
    }
} 