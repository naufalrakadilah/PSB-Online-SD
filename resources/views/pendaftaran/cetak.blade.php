<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran - {{ $pendaftaran->no_pendaftaran }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; padding: 2rem; color: #1E293B; background: white; }

        .print-container { max-width: 800px; margin: 0 auto; }

        .header { text-align: center; border-bottom: 3px solid #4F46E5; padding-bottom: 1.5rem; margin-bottom: 2rem; }
        .header h1 { font-size: 1.3rem; font-weight: 800; color: #4F46E5; text-transform: uppercase; letter-spacing: 0.05em; }
        .header h2 { font-size: 1.6rem; font-weight: 800; margin-top: 0.3rem; }
        .header p { color: #64748B; font-size: 0.85rem; margin-top: 0.5rem; }

        .no-reg { text-align: center; background: #F1F5F9; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; }
        .no-reg .label { color: #64748B; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .no-reg .number { font-size: 1.8rem; font-weight: 800; color: #4F46E5; letter-spacing: 0.05em; }

        .section { margin-bottom: 1.5rem; }
        .section-title { font-size: 0.9rem; font-weight: 700; color: #4F46E5; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 1px solid #E2E8F0; text-transform: uppercase; letter-spacing: 0.03em; }

        table { width: 100%; border-collapse: collapse; }
        table td { padding: 0.4rem 0; font-size: 0.85rem; vertical-align: top; }
        table td:first-child { width: 200px; color: #64748B; font-weight: 500; }
        table td:nth-child(2) { width: 15px; text-align: center; }
        table td:last-child { font-weight: 600; }

        .status-box { text-align: center; background: #FEF3C7; border: 1px solid #FDE68A; padding: 0.75rem; border-radius: 8px; margin: 1.5rem 0; }
        .status-box .status-label { font-weight: 700; color: #92400E; text-transform: uppercase; font-size: 0.85rem; }

        .footer-print { text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #E2E8F0; }
        .footer-print p { color: #94A3B8; font-size: 0.75rem; }

        .print-btn { background: #4F46E5; color: white; border: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9rem; margin-bottom: 2rem; display: block; margin-left: auto; margin-right: auto; }
        .print-btn:hover { background: #3730A3; }

        @media print {
            .print-btn { display: none !important; }
            body { padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <button class="print-btn" onclick="window.print()">
            🖨️ Cetak Bukti Pendaftaran
        </button>

        <div class="header">
            <h1>📚 SD Negeri Nusantara</h1>
            <h2>Bukti Pendaftaran Siswa Baru</h2>
            <p>Tahun Ajaran {{ $pendaftaran->tahun_ajaran }}</p>
        </div>

        <div class="no-reg">
            <div class="label">Nomor Pendaftaran</div>
            <div class="number">{{ $pendaftaran->no_pendaftaran }}</div>
        </div>

        <div class="section">
            <div class="section-title">Data Calon Siswa</div>
            <table>
                <tr><td>Nama Lengkap</td><td>:</td><td>{{ $pendaftaran->nama_lengkap }}</td></tr>
                <tr><td>NISN</td><td>:</td><td>{{ $pendaftaran->nisn ?: '-' }}</td></tr>
                <tr><td>NIK</td><td>:</td><td>{{ $pendaftaran->nik }}</td></tr>
                <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $pendaftaran->tempat_lahir }}, {{ $pendaftaran->tanggal_lahir->format('d/m/Y') }}</td></tr>
                <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $pendaftaran->jenis_kelamin }}</td></tr>
                <tr><td>Agama</td><td>:</td><td>{{ $pendaftaran->agama }}</td></tr>
                <tr><td>Alamat</td><td>:</td><td>{{ $pendaftaran->alamat_lengkap }}</td></tr>
                <tr><td>Asal Sekolah (TK)</td><td>:</td><td>{{ $pendaftaran->asal_sekolah ?: '-' }}</td></tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Data Orang Tua / Wali</div>
            <table>
                <tr><td>Nama Ayah</td><td>:</td><td>{{ $pendaftaran->nama_ayah }}</td></tr>
                <tr><td>Pekerjaan Ayah</td><td>:</td><td>{{ $pendaftaran->pekerjaan_ayah ?: '-' }}</td></tr>
                <tr><td>No. HP Ayah</td><td>:</td><td>{{ $pendaftaran->no_hp_ayah ?: '-' }}</td></tr>
                <tr><td>Nama Ibu</td><td>:</td><td>{{ $pendaftaran->nama_ibu }}</td></tr>
                <tr><td>Pekerjaan Ibu</td><td>:</td><td>{{ $pendaftaran->pekerjaan_ibu ?: '-' }}</td></tr>
                <tr><td>No. HP Ibu</td><td>:</td><td>{{ $pendaftaran->no_hp_ibu ?: '-' }}</td></tr>
                @if($pendaftaran->nama_wali)
                <tr><td>Nama Wali</td><td>:</td><td>{{ $pendaftaran->nama_wali }}</td></tr>
                <tr><td>Pekerjaan Wali</td><td>:</td><td>{{ $pendaftaran->pekerjaan_wali ?: '-' }}</td></tr>
                <tr><td>No. HP Wali</td><td>:</td><td>{{ $pendaftaran->no_hp_wali ?: '-' }}</td></tr>
                @endif
            </table>
        </div>

        <div class="status-box">
            <div class="status-label">Status: {{ $pendaftaran->status_badge['label'] }}</div>
        </div>

        <div class="footer-print">
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>Dokumen ini digenerate secara otomatis oleh sistem PSB Online SD Negeri Nusantara.</p>
            <p>Harap simpan nomor pendaftaran untuk keperluan pengecekan status.</p>
        </div>
    </div>
</body>
</html>
