<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
        }

        .header,
        .footer {
            text-align: center;
        }

        .content {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Telkom Indonesia</h2>
            <p>Nomor: {{ $nomor_surat }}</p>
            <p>Surabaya, {{ $date }}</p>
        </div>
        <div class="content">
            <p>Kepada Yth.<br>Bpk/Ibu. {{ $pranpc->nama }}<br>Perihal: Informasi Tagihan</p>
            <p>Dengan Hormat,</p>
            <p>Pertama-tama kami menyampaikan terima kasih atas kepercayaan perusahaan Bapak/Ibu tetap setia menggunakan
                jasa layanan PT. Telkom Indonesia, Tbk. di perusahaan yang Bapak/Ibu pimpin.</p>
            <p>Sebagaimana perihal tersebut di atas, kami sampaikan informasi tagihan atas {{ $pranpc->nama }} dengan
                nomor telepon {{ $pranpc->multi_kontak1 }} sebagai berikut:</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor</th>
                        <th>{{ $mintgk_bulan }}</th>
                        <th>{{ $maxtgk_bulan }}</th>
                        <th>Jumlah Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $pranpc->snd }}</td>
                        <td>RP. {{ number_format($pranpc->bill_bln1, 2, ',', '.') }}</td>
                        <td>RP. {{ number_format($pranpc->bill_bln, 2, ',', '.') }}</td>
                        <td>{{ $total_tagihan }}</td>
                    </tr>
                </tbody>
            </table>
            <p>Tagihan di atas sudah termasuk Ppn.</p>
            <p>Sehubungan dengan hal tersebut di atas, kami mohon tagihan dimaksud dapat segera dilakukan pelunasan
                pembayaran.</p>
            <p>Demikian kami sampaikan, atas perhatian dan kerjasamanya yang baik selama ini kami ucapkan terima kasih.
            </p>
            <p>Hormat Kami,<br>Witel Surabaya Selatan</p>
        </div>
        <div class="footer">
            <p>Munarti<br>Manager Business Service</p>
        </div>
    </div>
</body>

</html>
