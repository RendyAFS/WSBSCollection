<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    public $timestamps = false;

    // Jika tabel tidak memiliki kolom 'id' sebagai primary key, set properti berikut
    public $incrementing = false;
    protected $primaryKey = 'token'; // Atau nama kolom yang benar sebagai primary key

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];
}
