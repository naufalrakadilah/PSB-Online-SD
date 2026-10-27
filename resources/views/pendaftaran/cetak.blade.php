<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran - {{ $pendaftaran->no_pendaftaran }}</title>
    <style>
        @page { margin: 1.5cm; }
        * { margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1E293B; }

        .header { text-align: center; border-bottom: 3px solid #4F46E5; padding-bottom: 14px; margin-bottom: 18px; }
        .header h1 { font-size: 13px; font-weight: bold; color: #4F46E5; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { font-size: 18px; font-weight: bold; margin-top: 4px; }
        .header p { color: #64748B; font-size: 10px; margin-top: 4px; }

        .no-reg { text-align: center; background: #F1F5F9; padding: 10px; border-radius: 6px; margin-bottom: 18px; }
        .no-reg .label { color: #64748B; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .no-reg .number { font-size: 20px; font-weight: bold; color: #4F46E5; letter-spacing: 1px; margin-top: 2px; }

        .section { margin-bottom: 14px; }
        .section-title { font-size: 11px; font-weight: bold; color: #4F46E5; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 1px solid #E2E8F0; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; }
        table td { padding: 3px 0; vertical-align: top; }
        table td.label { width: 170px; color: #64748B; }
        table td.colon { width: 12px; text-align: center; }
        table td.value { font-weight: bold; }

        .status-box { text-align: center; background: #FEF3C7; border: 1px solid #FDE68A; padding: 8px; border-radius: 6px; margin: 16px 0; }
        .status-box .status-label { font-weight: bold; color: #92400E; text-transform: uppercase; }

        .footer { text-align: center; margin-top: 18px; padding-top: 12px; border-top: 1px solid #E2E8F0; color: #94A3B8; font-size: 9px; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SD Negeri Nusantara</h1>
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
            <tr><td class="label">Nama Lengkap</td><td class="colon">:</td><td class="value">{{ $pendaftaran->nama_lengkap }}</td></tr>
            <tr><td class="label">NIK</td><td class="colon">:</td><td class="value">{{ $pendaftaran->nik }}</td></tr>
            <tr><td class="label">Tempat, Tanggal Lahir</td><td class="colon">:</td><td class="value">{{ $pendaftaran->tempat_lahir }}, {{ $pendaftaran->tanggal_lahir->format('d/m/Y') }} ({{ $pendaftaran->umur }} tahun)</td></tr>
            <tr><td class="label">Jenis Kelamin</td><td class="colon">:</td><td class="value">{{ $pendaftaran->jenis_kelamin }}</td></tr>
            <tr><td class="label">Tinggi Badan</td><td class="colon">:</td><td class="value">{{ $pendaftaran->tinggi_badan ? $pendaftaran->tinggi_badan.' cm' : '-' }}</td></tr>
            <tr><td class="label">Agama</td><td class="colon">:</td><td class="value">{{ $pendaftaran->agama }}</td></tr>
            <tr><td class="label">Alamat Lengkap</td><td class="colon">:</td><td class="value">{{ $pendaftaran->alamat }}</td></tr>
            <tr><td class="label">Asal Sekolah (TK)</td><td class="colon">:</td><td class="value">{{ $pendaftaran->asal_sekolah ?: '-' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Data Orang Tua / Wali</div>
        <table>
            <tr><td class="label">Nama Ayah</td><td class="colon">:</td><td class="value">{{ $pendaftaran->nama_ayah }}</td></tr>
            <tr><td class="label">Pekerjaan Ayah</td><td class="colon">:</td><td class="value">{{ $pendaftaran->pekerjaan_ayah ?: '-' }}</td></tr>
            <tr><td class="label">No. HP Ayah</td><td class="colon">:</td><td class="value">{{ $pendaftaran->no_hp_ayah ?: '-' }}</td></tr>
            <tr><td class="label">Nama Ibu</td><td class="colon">:</td><td class="value">{{ $pendaftaran->nama_ibu }}</td></tr>
            <tr><td class="label">Pekerjaan Ibu</td><td class="colon">:</td><td class="value">{{ $pendaftaran->pekerjaan_ibu ?: '-' }}</td></tr>
            <tr><td class="label">No. HP Ibu</td><td class="colon">:</td><td class="value">{{ $pendaftaran->no_hp_ibu ?: '-' }}</td></tr>
            @if($pendaftaran->nama_wali)
                <tr><td class="label">Nama Wali</td><td class="colon">:</td><td class="value">{{ $pendaftaran->nama_wali }}</td></tr>
                <tr><td class="label">Pekerjaan Wali</td><td class="colon">:</td><td class="value">{{ $pendaftaran->pekerjaan_wali ?: '-' }}</td></tr>
                <tr><td class="label">No. HP Wali</td><td class="colon">:</td><td class="value">{{ $pendaftaran->no_hp_wali ?: '-' }}</td></tr>
            @endif
        </table>
    </div>

    <div class="status-box">
        <div class="status-label">Status: {{ $pendaftaran->status_badge['label'] }}</div>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}<br>
        Dokumen ini digenerate secara otomatis oleh sistem PSB Online SD Negeri Nusantara.<br>
        Harap simpan nomor pendaftaran untuk keperluan pengecekan status.
    </div>
</body>
</html>
