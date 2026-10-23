```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        .header {
            text-align: center;
            padding-bottom: 12px;
            border-bottom: 2px solid #2563eb;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0 0 5px;
            font-size: 20px;
            color: #1d4ed8;
            text-transform: uppercase;
        }

        .header p {
            margin: 3px 0;
            color: #6b7280;
            font-size: 10px;
        }

        .periode {
            margin-bottom: 15px;
            line-height: 1.7;
        }

        .ringkasan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .ringkasan td {
            width: 25%;
            padding: 10px;
            border: 1px solid #dbeafe;
            background-color: #eff6ff;
            vertical-align: top;
        }

        .ringkasan .label {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .ringkasan .angka {
            font-size: 15px;
            font-weight: bold;
            color: #1d4ed8;
        }

        h2 {
            font-size: 13px;
            margin-bottom: 8px;
            color: #111827;
        }

        .transaksi {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .transaksi th {
            background-color: #1d4ed8;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 6px;
            border: 1px solid #1d4ed8;
        }

        .transaksi td {
            padding: 7px 6px;
            border: 1px solid #d1d5db;
            vertical-align: top;
            word-wrap: break-word;
        }

        .transaksi tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .tengah {
            text-align: center;
        }

        .kanan {
            text-align: right;
        }

        .masuk {
            color: #15803d;
            font-weight: bold;
        }

        .keluar {
            color: #dc2626;
            font-weight: bold;
        }

        .kosong {
            text-align: center;
            padding: 20px;
            color: #6b7280;
        }

        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #d1d5db;
            font-size: 9px;
            color: #6b7280;
        }

        .halaman {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Laporan Inventaris Barang</h1>
        <p>Laporan transaksi barang masuk dan barang keluar</p>
        <p>Dokumen laporan sistem inventaris</p>
    </div>

    <div class="periode">
        <strong>Periode laporan:</strong>
        {{ $tanggalMulai ? \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') : 'Awal data' }}
        sampai
        {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') : 'Tanggal sekarang' }}
        <br>

        <strong>Jenis transaksi:</strong>
        @if (request('jenis') === 'masuk')
            Barang Masuk
        @elseif (request('jenis') === 'keluar')
            Barang Keluar
        @else
            Semua Transaksi
        @endif

        <br>
        <strong>Tanggal cetak:</strong> {{ $tanggalCetak }}
    </div>

    <h2>Ringkasan Inventaris</h2>

    <table class="ringkasan">
        <tr>
            <td>
                <div class="label">Total Jenis Produk</div>
                <div class="angka">
                    {{ number_format($ringkasan['totalProduk'], 0, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="label">Total Stok Saat Ini</div>
                <div class="angka">
                    {{ number_format($ringkasan['totalStok'], 0, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="label">Total Barang Masuk (Periode)</div>
                <div class="angka">
                    {{ number_format($ringkasan['totalMasuk'], 0, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="label">Total Barang Keluar (Periode)</div>
                <div class="angka">
                    {{ number_format($ringkasan['totalKeluar'], 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    <h2>Detail Transaksi</h2>

    <table class="transaksi">
        <thead>
            <tr>
                <th style="width: 5%;" class="tengah">No.</th>
                <th style="width: 13%;">Tanggal</th>
                <th style="width: 20%;">Nama Produk</th>
                <th style="width: 14%;">Kategori</th>
                <th style="width: 13%;">Jenis Transaksi</th>
                <th style="width: 10%;" class="kanan">Jumlah</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transaksi as $index => $item)
                <tr>
                    <td class="tengah">{{ $index + 1 }}</td>

                    <td>
                        {{ $item->tanggal
                            ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                            : '-' }}
                    </td>

                    <td>{{ $item->produk?->nama ?? 'Produk dihapus' }}</td>

                    <td>{{ $item->produk?->kategori ?? '-' }}</td>

                    <td>
                        @if ($item->jenis === 'masuk')
                            <span class="masuk">Barang Masuk</span>
                        @else
                            <span class="keluar">Barang Keluar</span>
                        @endif
                    </td>

                    <td class="kanan">
                        {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>

                    <td>{{ $item->keterangan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="kosong">
                        Tidak ada transaksi pada periode yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td>
                    Dicetak otomatis melalui Sistem Inventaris Barang.
                </td>
                <td class="halaman">
                    Tanggal cetak: {{ $tanggalCetak }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
```