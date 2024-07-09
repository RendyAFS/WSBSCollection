<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BillperExport implements FromCollection, WithHeadings
{
    protected $billpers;

    public function __construct($billpers)
    {
        $this->billpers = $billpers;
    }

    public function collection()
    {
        return $this->billpers;
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
            'Tahun-Bulan', // Tambahkan ini jika perlu
        ];
    }
}
