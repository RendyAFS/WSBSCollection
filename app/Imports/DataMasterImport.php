<?php

namespace App\Imports;

use App\Models\DataMaster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DataMasterImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        // Mengisi nilai kosong dengan "-"
        $eventSource = $row['event_source'] ?? 'N/A';
        $kwadran = $row['kwadran'] ?? 'N/A';
        $csto = $row['csto'] ?? 'N/A';
        $mobileContactTel = $row['mobile_contact_tel'] ?? 'N/A';
        $emailAddress = $row['email_address'] ?? 'N/A';
        $pelanggan = $row['pelanggan'] ?? 'N/A';
        $alamatPelanggan = $row['alamat_pelanggan'] ?? 'N/A';

        return new DataMaster([
            'event_source' => $eventSource,
            'kwadran' => $kwadran,
            'csto' => $csto,
            'mobile_contact_tel' => $mobileContactTel,
            'email_address' => $emailAddress,
            'pelanggan' => $pelanggan,
            'alamat_pelanggan' => $alamatPelanggan,
        ]);
    }

    public function chunkSize(): int
    {
        return 500; // Sesuaikan ukuran chunk sesuai kebutuhan
    }
}
