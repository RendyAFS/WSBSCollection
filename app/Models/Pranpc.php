<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pranpc extends Model
{
    use HasFactory;
    protected $fillable = [
        'snd', 'sto', 'nama', 'alamat', 'bill_bln', 'bill_bln1', 'mintgk', 'maxtgk', 'multi_kontak1', 'email', 'status_pembayaran', 'users_id', 'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function salesReports()
    {
        return $this->hasMany(SalesReport::class, 'pranpc_id');
    }
}
