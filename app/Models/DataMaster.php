<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_source', 'kwadran', 'csto', 'mobile_contact_tel', 'email_address', 'pelanggan', 'alamat_pelanggan'
    ];
}
