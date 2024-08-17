<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Exists;

class SalesReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id', 'snd', 'witel', 'waktu_visit', 'voc_kendalas_id', 'follow_up', 'evidence_sales', 'evidence_pembayaran', 'billper_id', 'existing_id', 'pranpc_id', 'jmlh_visit'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function vockendals()
    {
        return $this->belongsTo(VocKendala::class, 'voc_kendalas_id');
    }

    public function billpers()
    {
        return $this->belongsTo(Billper::class, 'billper_id');
    }

    public function existings()
    {
        return $this->belongsTo(Existing::class, 'existing_id');
    }

    public function pranpcs()
    {
        return $this->belongsTo(Pranpc::class, 'pranpc_id');
    }
}
