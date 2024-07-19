<?php

namespace App\Imports;

use App\Models\DataMaster;
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

            // Dapatkan nilai snd dari baris
            $snd = $row['snd'] ?? 'N/A';

            // Cari nilai csto yang cocok dari tabel data_masters berdasarkan event_source
            $dataMaster = DataMaster::where('event_source', $snd)->first();
            $sto = $dataMaster ? $dataMaster->csto : 'N/A';

            // Hapus karakter non-numerik dari bill_bln dan bill_bln1
            $billBln = isset($row['bill_bln']) ? preg_replace('/[^0-9]/', '', $row['bill_bln']) : 'N/A';
            $billBln1 = isset($row['bill_bln1']) ? preg_replace('/[^0-9]/', '', $row['bill_bln1']) : 'N/A';

            return new TempPranpc([
                'snd' => $snd,
                'sto' => $sto,
                'nama' => $row['nama'] ?? 'N/A',
                'alamat' => $row['alamat'] ?? 'N/A',
                'bill_bln' => $billBln,
                'bill_bln1' => $billBln1,
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
