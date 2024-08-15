<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use NumberFormatter;

class AllExport implements FromCollection, WithHeadings
{
    protected $allData;

    public function __construct($allData)
    {
        $this->allData = $allData;
    }

    public function collection()
    {
        // Mengubah format saldo menjadi rupiah
        $formattedData = $this->allData->map(function ($item) {
            // Konversi saldo menjadi float jika masih dalam format string
            $saldo = floatval($item['saldo']);

            // Format saldo menjadi Rupiah
            $item['saldo'] = 'Rp' . number_format($saldo, 2, ',', '.');

            return $item;
        });

        return $formattedData;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'No Inet',
            'Saldo',
            'No Tlf',
            'Email',
            'STO',
            'Umur Customer',
            'Produk',
            'Status Pembayaran',
            'Nper', // Tambahkan ini jika perlu
        ];
    }
}
