<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id', 'snd', 'witel', 'waktu_visit', 'voc_kendalas_id', 'follow_up', 'status_wo', 'evidence_sales', 'evidence_pembayaran'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function vockendals()
    {
        return $this->belongsTo(VocKendala::class, 'voc_kendalas_id');
    }
}
