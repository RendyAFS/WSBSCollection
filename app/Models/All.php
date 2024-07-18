<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class All extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper', 'users_id', 'evidence', 'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
