<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
            /* Mengatur ukuran halaman menjadi A4 */
            margin: 0;
            margin-top: 20pt;
            margin-bottom: 0pt;
            margin-left: 30pt;
            margin-right: 30pt;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            /* Ukuran font untuk seluruh halaman */
            margin: 0;
            /* Menghapus margin default */
            padding-top: 20pt;
            padding-bottom: 0pt;
            padding-left: 30pt;
            padding-right: 30pt;
        }

        .container {
            width: 100%;
            box-sizing: border-box;
            /* Agar padding tidak mempengaruhi ukuran container */
        }

        .header {
            display: block;
            margin-bottom: 20px;
        }

        .header-text {
            display: inline-block;
            vertical-align: bottom;
            width: 75%;
            margin-top: 60px;
        }

        .header-image {
            display: inline-block;
            vertical-align: top;
            width: 18%;
            text-align: right;
        }

        .header-image img {
            max-width: 100%;
            height: auto;
        }

        .content {
            clear: both;
            margin-top: 20px;
        }

        .table-contain {
            padding-left: 30pt;
            padding-right: 30pt;
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

        .tight-margin {
            margin-top: 0;
            margin-bottom: 0;
            margin-left: 1%;
        }

        .footer-1 {
            width: 100%;
            margin-top: 20px;
        }

        .footer-content {
            float: right;
            font-size: 10pt;
            /* Ukuran font untuk footer */
        }

        .footer-image {
            display: inline-block;
            margin-left: 20px;
        }

        .footer-image img {
            width: 125px;
            height: auto;
        }

        .footer-2 {
            width: 100%;
            margin-top: 285px;
        }

        .footer-content-2 {
            float: left;
            font-size: 10pt;
            /* Ukuran font untuk footer */
        }

        .contact-list {
            margin-top: 10px;
            font-family: Arial, sans-serif;
            font-size: 6pt;
            /* Ukuran font untuk daftar kontak */
        }

        .contact-list ul {
            margin: 0;
            padding: 0;
            margin-left: 12pt;
            margin-top: -6px;
            list-style-type: disc;
        }

        .contact-list li {
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-text">
                <p>Nomor : {{ $nomor_surat }}</p>
            </div>
            <div class="header-image">
                <img src="{{ $image_src }}" alt="header-image">
            </div>
        </div>
        <div class="content">
            <p>Surabaya, {{ $date }}</p>
            <p>Kepada Yth.<br>Bpk/Ibu. {{ $all->nama }}<br>Perihal: Informasi Tagihan</p>
            <p>Dengan Hormat,</p>
            <p>Pertama-tama kami menyampaikan terima kasih atas kepercayaan perusahaan Bapak/Ibu tetap setia menggunakan
                jasa layanan PT. Telkom Indonesia, Tbk. di perusahaan yang Bapak/Ibu pimpin.</p>
            <p>Sebagaimana perihal tersebut di atas, kami sampaikan informasi tagihan atas {{ $all->nama }} dengan
                nomor telepon {{ $all->no_tlf }} sebagai berikut:</p>

            <div class="table-contain">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>{{ $nper }}</th>
                            <th>Jumlah Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ $all->no_inet }}</td>
                            <td>RP. {{ number_format($all->saldo, 2, ',', '.') }}</td>
                            <td>{{ $total_tagihan }}</td>
                        </tr>
                    </tbody>
                </table>
                <p class="tight-margin">Tagihan di atas sudah termasuk Ppn.</p>
            </div>
            <p>Sehubungan dengan hal tersebut di atas, kami mohon tagihan dimaksud dapat segera dilakukan pelunasan
                pembayaran.</p>
            <p>Demikian kami sampaikan, atas perhatian dan kerjasamanya yang baik selama ini kami ucapkan terima kasih.
            </p>
        </div>
        <div class="footer-1">
            <div class="footer-content">
                <p>Hormat Kami,<br>Witel Surabaya Selatan</p>
                <br>
                <div class="footer-image">
                    <img src="{{ $image_src }}" alt="footer-image">
                </div>
                <p style="font-weight: normal;">
                    <s style="text-decoration: none"> Munarti </s> <br>
                    <s style="text-decoration: none"> Manager Business Service </s> <br>
                    Witel Surabaya Selatan
                </p>

            </div>
        </div>
        <div class="footer-2">
            <div class="footer-content-2">
                <div class="contact-list">
                    <p>Contact person:<br>
                        Collection witel SBS: 085176897993<br>
                        Call center:</p>
                    <ul>
                        <li>1500250</li>
                        <li>08001835566</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
