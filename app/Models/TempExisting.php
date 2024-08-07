<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempExisting extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'alamat', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper'
    ];
}
