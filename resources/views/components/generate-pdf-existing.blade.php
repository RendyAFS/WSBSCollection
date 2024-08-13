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
            padding-top: 20pt;
            padding-bottom: 0pt;
            padding-left: 30pt;
            padding-right: 30pt;
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
            opacity: 0.5;
        }

        .content {
            clear: both;
            margin-top: -20px;
        }
        .content p{
            text-align: justify;
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
            margin-top: 10px;
        }

        .footer-content {
            float: left;
            font-size: 10pt;
            /* Ukuran font untuk footer */
        }

        .footer-content p {
            margin: 0px;
            margin-bottom: 20px;
        }

        .footer-image {
            display: inline-block;
            margin-left: 20px;
            margin-bottom: 45px;
        }

        .footer-image img {
            width: 125px;
            height: auto;
        }

        .footer-2 {
            width: 100%;
            margin-top: 175px;
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

        .contact-list li p {
            margin: 0px;
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
            <p><strong>Kepada Yth.</strong><br>
                <strong>Bpk/Ibu. {{ $existing->nama }} </strong><br>
                <strong>{{$existing->alamat}}</strong><br>
            </p>
            <p>Perihal: <strong> Konfirmasi Data Tunggakan {{ $existing->no_inet }}</strong></p>
            <p>Dengan Hormat,</p>
            <p>Pertama kami sampaikan rasa terima kasih atas kepercayaan perusahaan Bapak/Ibu yang senantiasa
                menggunakan jasa layanan Telkom Solution sebagai sarana untuk membangun komunikasi baik internal maupun
                eksternal.</p>
            <p>Berdasarkan catatan data kami sampai dengan posisi {{$date}} untuk nomor layanan
                <strong>{{ $existing->no_inet }}</strong> masih terdapat
                beberapa tunggakan sebagai berikut:
            </p>

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
                            <td>{{ $existing->no_inet }}</td>
                            <td>RP. {{ number_format($existing->saldo, 2, ',', '.') }}</td>
                            <td>{{ $total_tagihan }}</td>
                        </tr>
                    </tbody>
                </table>
                <p class="tight-margin">Tagihan di atas sudah termasuk Ppn.</p>
            </div>
            <p>Sehubungan dengan hal tsb diatas, kami mohon tagihan Telkom Solution dimaksud dapat segera dilakukan
                penyelesaiannya melalui : Online Payment Point Bank (Internet Banking, Mobile Banking, ATM, loket bank)
                pada seluruh bank domestik melalui menu pembayaran tagihan atau dapat melakukan pembayaran secara
                Setoran Tunai melalui Plasa Telkom Group, Kantor Pos, Loket Bank dan gerai Indomaret, Alfamart).
            </p>
            <p>
                Perlu kami informasikan, apabila perusahaan Bapak/Ibu tidak menyelesaikan seluruh kewajiban tersebut di
                atas maka kami akan melakukan <strong>pengisoliran (pemutusan layanan)</strong> tanpa pemberitahuan
                terlebih dahulu.
                <strong>Apabila Bapak/Ibu telah melakukan pembayaran</strong> tunggakan sebelum surat ini diterima, bisa
                untuk
                mengabaikan surat ini dan mohon bantuan untuk bukti transfer agar dikirim atau menghubungi kami di nomor
                Whatsapp 0812-5296-5434.
            </p>
            <p>
                Demikian kami sampaikan, atas perhatian dan kerjasamanya yang baik selama ini kami ucapkan terima kasih.
            </p>
        </div>
        <div class="footer-1">
            <div class="footer-content">
                <p>Hormat Kami,<br>Witel Suramadu</p>
                <br>
                <div class="footer-image">
                    {{-- <img src="{{ $image_src }}" alt="footer-image"> --}}
                </div>
                <p style="font-weight: normal; margin:0px; ">
                    <s style="text-decoration: underline"> HALIS RIDHO SANJAYA </s> <br>
                    <s style="text-decoration: none"> MGR SHARED SERVICE & GENERAL SUPPORT </s> <br>
                    WITEL SURAMADU
                </p>

            </div>
        </div>
        <div class="footer-2">
            <div class="footer-content-2">
                <div class="contact-list">
                    <p>Contact person:<br>
                        Collection Witel Suramadu: 0812-5296-5434<br>
                        Call center:</p>
                    <ul>
                        <li>1500250</li>
                        <li>0812-5296-5434</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
