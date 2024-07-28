<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportBillperExisting implements FromCollection, WithHeadings
{
    protected $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        return $this->reports->map(function ($item) {
            return [
                'SND' => $item->snd,
                'Nama Customer' => $item->alls->nama,
                'Waktu Visit' => $item->waktu_visit,
                'Nama Sales' => $item->user->name,
                'VOC & Kendala' => $item->vockendals->voc_kendala,
                'Follow Up' => $item->follow_up,
                'Evidence Sales' => $item->evidence_sales,
                'Evidence Pembayaran' => $item->evidence_pembayaran
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SND',
            'Nama Customer',
            'Waktu Visit',
            'Nama Sales',
            'VOC & Kendala',
            'Follow Up',
            'Evidence Sales',
            'Evidence Pembayaran'
        ];
    }
}
