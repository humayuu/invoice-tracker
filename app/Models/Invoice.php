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
}
