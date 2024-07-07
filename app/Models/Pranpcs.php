<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pranpcs extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer'
    ];
}
