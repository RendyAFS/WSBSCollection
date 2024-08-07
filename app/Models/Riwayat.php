<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;
    protected $fillable = [
        'deskripsi_riwayat', 'tanggal_riwayat', 'riwayat_id'
    ];
}
