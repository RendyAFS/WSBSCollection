<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billper extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'alamat', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper', 'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function salesReports()
    {
        return $this->hasMany(SalesReport::class, 'billper_id');
    }
}

