<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($tipe == 'koperasi')
            Laporan Keuangan Koperasi Warga
        @else
            Laporan Keuangan - {{ ucfirst($tipe) }}
        @endif
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            margin: 0;
            padding: 40px;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            border-bottom: 3px double #cbd5e1;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-logo {
            font-size: 24px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -1px;
        }
        .header-info {
            text-align: right;
        }
        .header-info h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .header-info p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #64748b;
        }
        .title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 10px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: 700;
        }
        .badge-pemasukan {
            color: #10b981;
            font-weight: 600;
        }
        .badge-pengeluaran {
            color: #ef4444;
            font-weight: 600;
        }
        .summary-box {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 40px;
            display: grid;
            grid-template-cols: repeat(3, 1fr);
            gap: 20px;
            border: 1px solid #e2e8f0;
        }
        .summary-card {
            display: flex;
            flex-direction: column;
        }
        .summary-card span {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        .summary-card strong {
            font-size: 16px;
            font-weight: 800;
            margin-top: 4px;
        }
        .color-pemasukan { color: #10b981; }
        .color-pengeluaran { color: #ef4444; }
        .color-saldo { color: #2563eb; }
        
        .signature-container {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 200px;
            text-align: center;
        }
        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #64748b;
            padding-top: 6px;
            font-weight: 700;
            color: #0f172a;
        }
        .signature-title {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        
        /* Buttons that shouldn't show on print */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
        .btn-action-container {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 9999;
        }
        .btn-action {
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-action:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }
        .btn-close {
            background-color: #64748b;
        }
        .btn-close:hover {
            background-color: #475569;
        }
    </style>
</head>
<body>

    <!-- Controls for user (hidden in print mode) -->
    <div class="btn-action-container no-print">
        <button onclick="window.print()" class="btn-action">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="btn-action btn-close">
            Tutup Halaman
        </button>
    </div>

    <!-- Corporate RT Header -->
    <div class="header">
        <div class="header-logo">
            GUYUB
        </div>
        <div class="header-info">
            <h2>{{ $namaRT }}</h2>
            <p>{{ $alamatRT }}</p>
            <p>Tanggal Unduh: {{ date('d-m-Y H:i') }}</p>
        </div>
    </div>

    <!-- Title -->
    <div class="title">
        @if($tipe == 'koperasi')
            Laporan Keuangan Koperasi Warga
        @else
            Laporan Transaksi Keuangan - {{ str_replace('-', ' ', $tipe) }}
        @endif
    </div>

    <!-- Summary Box -->
    <div class="summary-box">
        <div class="summary-card">
            <span>Total Pemasukan</span>
            <strong class="color-pemasukan">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong>
        </div>
        <div class="summary-card">
            <span>Total Pengeluaran</span>
            <strong class="color-pengeluaran">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong>
        </div>
        <div class="summary-card">
            <span>Saldo Bersih</span>
            <strong class="color-saldo">Rp {{ number_format($saldoBersih, 0, ',', '.') }}</strong>
        </div>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 45%;">Keterangan</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 10%;">Jenis</th>
                <th style="width: 15%; text-align: right;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                <td>{{ $row->keterangan }}</td>
                <td>{{ $row->kategori }}</td>
                <td>
                    @if($row->jenis == 'pemasukan')
                        <span class="badge-pemasukan">Pemasukan</span>
                    @else
                        <span class="badge-pengeluaran">Pengeluaran</span>
                    @endif
                </td>
                <td class="text-right font-bold {{ $row->jenis == 'pemasukan' ? 'badge-pemasukan' : 'badge-pengeluaran' }}">
                    Rp {{ number_format($row->nominal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signature Boxes -->
    <div class="signature-container">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <div class="signature-line">Ketua RT</div>
            <div class="signature-title">{{ $namaRT }}</div>
        </div>
        <div class="signature-box">
            <p>Dibuat oleh,</p>
            <div class="signature-line">Bendahara RT</div>
            <div class="signature-title">GUYUB</div>
        </div>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
