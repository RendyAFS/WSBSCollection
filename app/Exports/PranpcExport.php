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
        $formattedData = $this->pranpcs->map(function ($item) {
            // Konversi Bill Bln dan Bill Bln1 menjadi float jika masih dalam format string
            $billBln = floatval($item['bill_bln']);
            $billBln1 = floatval($item['bill_bln1']);

            // Format Bill Bln dan Bill Bln1 menjadi Rupiah
            $item['bill_bln'] = 'Rp' . number_format($billBln, 2, ',', '.');
            $item['bill_bln1'] = 'Rp' . number_format($billBln1, 2, ',', '.');

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
