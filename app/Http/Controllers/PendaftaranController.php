<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    private const HURUF_SAJA = '/^[\pL\s.,\'-]+$/u';

    private const ANGKA_SAJA = '/^[0-9]+$/';

    public function create()
    {
        return view('pendaftaran.create');
    }

    public function store(Request $request)
    {
        try {
            $batasLahirTermuda = now()->subYears(5)->toDateString();
            $batasLahirTertua = now()->subYears(12)->toDateString();

            $validator = Validator::make($request->all(), [
                'nama_lengkap' => ['required', 'string', 'max:255', 'regex:'.self::HURUF_SAJA],
                'nik' => 'required|digits:16|unique:pendaftaran,nik',
                'tempat_lahir' => ['required', 'string', 'max:100', 'regex:'.self::HURUF_SAJA],
                'tanggal_lahir' => "required|date|before_or_equal:{$batasLahirTermuda}|after:{$batasLahirTertua}",
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tinggi_badan' => 'required|numeric|min:50|max:200',
                'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
                'alamat' => 'required|string|max:500',
                'asal_sekolah' => 'nullable|string|max:255',

                'nama_ayah' => ['required', 'string', 'max:255', 'regex:'.self::HURUF_SAJA],
                'pekerjaan_ayah' => ['nullable', 'string', 'max:100', 'regex:'.self::HURUF_SAJA],
                'no_hp_ayah' => ['nullable', 'regex:'.self::ANGKA_SAJA, 'max:12'],
                'nama_ibu' => ['required', 'string', 'max:255', 'regex:'.self::HURUF_SAJA],
                'pekerjaan_ibu' => ['nullable', 'string', 'max:100', 'regex:'.self::HURUF_SAJA],
                'no_hp_ibu' => ['nullable', 'regex:'.self::ANGKA_SAJA, 'max:12'],
                'nama_wali' => ['nullable', 'string', 'max:255', 'regex:'.self::HURUF_SAJA],
                'pekerjaan_wali' => ['nullable', 'string', 'max:100', 'regex:'.self::HURUF_SAJA],
                'no_hp_wali' => ['nullable', 'regex:'.self::ANGKA_SAJA, 'max:12'],
            ], [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf.',
                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus 16 digit angka.',
                'nik.unique' => 'NIK sudah terdaftar. Silakan gunakan NIK lain.',
                'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
                'tempat_lahir.regex' => 'Tempat lahir hanya boleh berisi huruf.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tanggal_lahir.before_or_equal' => 'Umur calon siswa minimal 5 tahun pada tanggal pendaftaran.',
                'tanggal_lahir.after' => 'Umur calon siswa maksimal 11 tahun pada tanggal pendaftaran.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
                'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
                'tinggi_badan.min' => 'Tinggi badan minimal 50 cm.',
                'tinggi_badan.max' => 'Tinggi badan maksimal 200 cm.',
                'agama.required' => 'Agama wajib dipilih.',
                'alamat.required' => 'Alamat lengkap wajib diisi.',
                'nama_ayah.required' => 'Nama ayah wajib diisi.',
                'nama_ayah.regex' => 'Nama ayah hanya boleh berisi huruf.',
                'pekerjaan_ayah.regex' => 'Pekerjaan ayah hanya boleh berisi huruf.',
                'no_hp_ayah.regex' => 'No. HP ayah hanya boleh berisi angka.',
                'no_hp_ayah.max' => 'No. HP ayah maksimal 12 digit.',
                'nama_ibu.required' => 'Nama ibu wajib diisi.',
                'nama_ibu.regex' => 'Nama ibu hanya boleh berisi huruf.',
                'pekerjaan_ibu.regex' => 'Pekerjaan ibu hanya boleh berisi huruf.',
                'no_hp_ibu.regex' => 'No. HP ibu hanya boleh berisi angka.',
                'no_hp_ibu.max' => 'No. HP ibu maksimal 12 digit.',
                'nama_wali.regex' => 'Nama wali hanya boleh berisi huruf.',
                'pekerjaan_wali.regex' => 'Pekerjaan wali hanya boleh berisi huruf.',
                'no_hp_wali.regex' => 'No. HP wali hanya boleh berisi angka.',
                'no_hp_wali.max' => 'No. HP wali maksimal 12 digit.',
            ]);

            if ($validator->fails()) {
                Log::warning('Validasi pendaftaran gagal', [
                    'errors' => $validator->errors()->toArray(),
                ]);

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $validator->validated();

            $data['no_pendaftaran'] = Pendaftaran::generateNoPendaftaran();
            $data['tahun_ajaran'] = '2026/2027';
            $data['status'] = 'menunggu';

            $pendaftaran = Pendaftaran::create($data);

            Log::info('Pendaftaran berhasil disimpan', [
                'id' => $pendaftaran->id,
                'no_pendaftaran' => $pendaftaran->no_pendaftaran,
                'nama' => $pendaftaran->nama_lengkap,
            ]);

            return redirect()->route('pendaftaran.success', $pendaftaran->no_pendaftaran)
                ->with('success', 'Pendaftaran berhasil! Simpan nomor pendaftaran Anda.');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan pendaftaran', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan pendaftaran. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function success(string $noPendaftaran)
    {
        $pendaftaran = Pendaftaran::where('no_pendaftaran', $noPendaftaran)->firstOrFail();

        return view('pendaftaran.success', compact('pendaftaran'));
    }

    public function cetak(string $noPendaftaran): Response
    {
        $pendaftaran = Pendaftaran::where('no_pendaftaran', $noPendaftaran)->firstOrFail();

        return Pdf::loadView('pendaftaran.cetak', compact('pendaftaran'))
            ->setPaper('a4', 'portrait')
            ->download("Bukti-Pendaftaran-{$pendaftaran->no_pendaftaran}.pdf");
    }

    public function cekStatus(Request $request)
    {
        $noPendaftaran = $request->input('no_pendaftaran');
        $pendaftaran = null;

        if ($noPendaftaran) {
            $pendaftaran = Pendaftaran::where('no_pendaftaran', $noPendaftaran)->first();
        }

        return view('pendaftaran.cek-status', compact('pendaftaran', 'noPendaftaran'));
    }
}
