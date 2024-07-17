<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\TempPranpc;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PranpcImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        // Check if the WITEL column exists and has the value 'SURABAYA SELATAN'
        if (isset($row['witel']) && strtolower(trim($row['witel'])) === 'surabaya selatan') {
            $multiKontak1 = $row['multi_kontak1'] ?? $row['no_hp'] ?? 'N/A';
            $email = isset($row['email']) && !empty($row['email']) ? $row['email'] : 'N/A';

            // Ubah format mintgk dan maxtgk jika tersedia
            $mintgk = isset($row['mintgk']) ? substr($row['mintgk'], 0, 4) . '-' . substr($row['mintgk'], 4, 2) : 'N/A';
            $maxtgk = isset($row['maxtgk']) ? substr($row['maxtgk'], 0, 4) . '-' . substr($row['maxtgk'], 4, 2) : 'N/A';

            return new TempPranpc([
                'snd' => $row['snd'] ?? 'N/A',
                'nama' => $row['nama'] ?? 'N/A',
                'alamat' => $row['alamat'] ?? 'N/A',
                'bill_bln' => $row['bill_bln'] ?? 'N/A',
                'bill_bln1' => $row['bill_bln1'] ?? 'N/A',
                'mintgk' => $mintgk,
                'maxtgk' => $maxtgk,
                'multi_kontak1' => $multiKontak1,
                'email' => $email,
                'status_pembayaran' => 'Unpaid',
            ]);
        }
        // Return null if the condition is not met
        return null;
    }

    public function chunkSize(): int
    {
        return 1000; // Sesuaikan ukuran chunk sesuai kebutuhan
    }
}
