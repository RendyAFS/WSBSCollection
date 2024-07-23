<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocKendala extends Model
{
    use HasFactory;

    protected $fillable = [
        'voc_kendala'
    ];

    public function salesreports()
    {
        return $this->hasMany(SalesReport::class, 'voc_kendala_id');
    }
}
