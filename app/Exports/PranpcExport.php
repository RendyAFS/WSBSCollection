<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use NumberFormatter;

class PranpcExport implements FromCollection, WithHeadings
{
    protected $pranpcs;

    public function __construct($pranpcs)
    {
        $this->pranpcs = $pranpcs;
    }

    public function collection()
    {
        // Mengubah format Bill Bln dan Bill Bln1 menjadi Rupiah
        $formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);

        $formattedData = $this->pranpcs->map(function ($item) use ($formatter) {
            $item['bill_bln'] = $formatter->formatCurrency($item['bill_bln'], 'IDR');
            $item['bill_bln1'] = $formatter->formatCurrency($item['bill_bln1'], 'IDR');
            return $item;
        });

        return $formattedData;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'No Inet',
            'Alamat',
            'Bill Bln',
            'Bill Bln1',
            'Mintgk',
            'Maxtgk',
            'No HP',
            'Email',
            'Status Pembayaran',
        ];
    }
}
