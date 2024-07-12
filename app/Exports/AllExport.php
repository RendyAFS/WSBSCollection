<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllExport implements FromCollection, WithHeadings
{
    protected $alls;

    public function __construct($alls)
    {
        $this->alls = $alls;
    }

    public function collection()
    {
        return $this->alls;
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
