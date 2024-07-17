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
        $formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);

        $formattedData = $this->allData->map(function ($item) use ($formatter) {
            // Konversi saldo menjadi float jika masih dalam format string
            $saldo = floatval($item['saldo']);

            // Format currency menggunakan NumberFormatter
            $formattedSaldo = $formatter->formatCurrency($saldo, 'IDR');

            // Menghapus semua spasi dari hasil format
            $formattedSaldo = preg_replace('/\s+/', '', $formattedSaldo);

            // Update nilai saldo dalam $item dengan saldo yang sudah diformat
            $item['saldo'] = $formattedSaldo;

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
