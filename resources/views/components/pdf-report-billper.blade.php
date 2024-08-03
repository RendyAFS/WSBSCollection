<!DOCTYPE html>
<html>

<head>
    <title>Data Billper</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
        }

        .card-subtitle {
            font-size: 16px;
            color: grey;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .text-bg-danger {
            color: red;
        }

        .text-bg-secondary {
            background-color: lightgrey;
            padding: 0.5rem;
            border-radius: 4px;
        }

        .img-preview {
            max-width: 100px;
            max-height: 100px;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #e9ecef;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-select {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .badge {
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="card-body">
        <div class="contain-header mb-3">
            <h5 class="card-title">{{ $all->nama }}</h5>
            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $all->no_inet }}</h6>
        </div>
        <hr class="border border-dark border-3 opacity-75 my-4">
        <div class="contain-form">
            <div class="mb-3">
                <label for="nama" class="form-label fw-bold">Nama</label>
                <div class="text-bg-secondary">{{ $all->nama }}</div>
            </div>
            <div class="mb-3">
                <label for="no_inet" class="form-label fw-bold">No. Inet</label>
                <div class="text-bg-secondary">{{ $all->no_inet }}</div>
            </div>
            <div class="mb-3">
                <label for="status_pembayaran" class="form-label fw-bold">Status Pembayaran</label>
                <div class="text-bg-secondary">{{ $all->status_pembayaran }}</div>
            </div>
            <div class="mb-3">
                <label for="no_tlf" class="form-label fw-bold">Nomor Telfon</label>
                <div class="text-bg-secondary">{{ $all->no_tlf }}</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <div class="text-bg-secondary">{{ $all->email }}</div>
            </div>
            <?php

            // Define a helper function to format the number as Indonesian Rupiah
            if (!function_exists('formatRupiah')) {
                function formatRupiah($amount)
                {
                    return 'Rp ' . number_format($amount, 0, ',', '.');
                }
            }
            ?>
            <div class="mb-3">
                <label for="saldo" class="form-label fw-bold">Saldo</label>
                <div class="text-bg-secondary">{{ formatRupiah($all->saldo) }}</div>
            </div>
            <div class="mb-3">
                <label for="sto" class="form-label fw-bold">STO</label>
                <div class="text-bg-secondary">{{ $all->sto }}</div>
            </div>
            <div class="mb-3">
                <label for="umur_customer" class="form-label fw-bold">Umur Customer</label>
                <div class="text-bg-secondary">{{ $all->umur_customer }}</div>
            </div>
            <div class="mb-3">
                <label for="produk" class="form-label fw-bold">Produk</label>
                <div class="text-bg-secondary">{{ $all->produk }}</div>
            </div>
            <div class="mb-3">
                <label for="nper" class="form-label fw-bold">NPER</label>
                <div class="text-bg-secondary">{{ $all->nper }}</div>
            </div>
        </div>

        <div class="page-break"></div> <!-- This will create a page break when printing -->
        <div class="card-body">
            <div class="contain-header mb-3">
                <h5 class="card-title">{{ $all->user ? $all->user->name : 'Tidak ada' }}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary"> {{ $all->user ? $all->user->nik : 'Tidak ada' }}
                </h6>
            </div>
            <hr class="border border-dark border-3 opacity-75 my-4">

            <div class="contain-form">
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <div class="text-bg-secondary">{{ $all->user ? $all->user->email : 'Tidak ada' }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">No Telfon</label>
                    <div class="text-bg-secondary">{{ $all->user ? $all->user->no_hp : 'Tidak ada' }}</div>
                </div>

                {{-- Report --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Waktu Visit</label>
                    <div class="text-bg-secondary">
                        {{ $sales_report->waktu_visit ? \Carbon\Carbon::parse($sales_report->waktu_visit)->format('Y-m-d H:i:s') : 'Tidak ada' }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Voc Kendala</label>
                    <div class="text-bg-secondary">
                        @foreach ($voc_kendala as $kendala)
                            @if ($sales_report->voc_kendalas_id == $kendala->id)
                                {{ $kendala->voc_kendala }}
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Follow Up</label>
                    <div class="text-bg-secondary">{{ $sales_report->follow_up ?? 'Tidak ada' }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Evidence Sales</label>
                    <div class="mt-2">
                        @if ($sales_report && $sales_report->evidence_sales)
                            <a href="{{ asset('storage/file_evidence/' . $sales_report->evidence_sales) }}"
                                target="_blank">Lihat Gambar</a>
                        @else
                            <span class="badge text-bg-danger">Report belum ada</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Evidence Pembayaran</label>
                    <div class="mt-2">
                        @if ($sales_report && $sales_report->evidence_pembayaran)
                            <a href="{{ asset('storage/file_evidence/' . $sales_report->evidence_pembayaran) }}"
                                target="_blank">Lihat Gambar</a>
                        @else
                            <span class="badge text-bg-danger">Report belum ada</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
