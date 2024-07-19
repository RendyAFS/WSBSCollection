<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPranpc extends Model
{
    use HasFactory;
    protected $fillable = [
        'snd', 'sto', 'nama', 'alamat', 'bill_bln', 'bill_bln1', 'mintgk', 'maxtgk', 'multi_kontak1', 'email', 'status_pembayaran'
    ];
}
