<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'po_no',
        'purchase_invoice_no',
        'description',
        'amount',
        'due_date',
        'status',
    ];

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class);
    }
}
