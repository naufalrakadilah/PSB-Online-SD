<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * PendaftaranController - Controller untuk proses pendaftaran siswa baru.
 *
 * Kompetensi:
 * - J.620100.017.02 - Mengimplementasikan Pemrograman Terstruktur
 * - J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek
 * - J.620100.021.02 - Menerapkan Akses Basis Data
 * - J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
 * - J.620100.025.02 - Melakukan Debugging
 * - J.620100.030.02 - Menerapkan Pemrograman Multimedia (Upload file)
 */
class PendaftaranController extends Controller
{
    /**
     * Menampilkan form pendaftaran siswa baru.
     *
     * @return View
     */
    public function create()
    {
        return view('pendaftaran.create');
    }

    /**
     * Menyimpan data pendaftaran siswa baru.
     * Melakukan validasi, upload file, dan penyimpanan ke database.
     *
     * Kompetensi:
     * - J.620100.022.02 - Algoritma validasi dan pemrosesan data
     * - J.620100.030.02 - Upload dan manajemen file multimedia
     * - J.620100.025.02 - Error handling dan debugging
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi input - Kompetensi Pemrograman Terstruktur
            $validator = Validator::make($request->all(), [
                // Data Siswa
                'nama_lengkap' => 'required|string|max:255',
                'nisn' => 'nullable|string|max:20',
                'nik' => 'required|string|size:16|unique:pendaftaran,nik',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date|before:today',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
                'alamat' => 'required|string',
                'rt' => 'nullable|string|max:5',
                'rw' => 'nullable|string|max:5',
                'kelurahan' => 'nullable|string|max:100',
                'kecamatan' => 'nullable|string|max:100',
                'kota' => 'nullable|string|max:100',
                'kode_pos' => 'nullable|string|max:10',
                'asal_sekolah' => 'nullable|string|max:255',

                // Data Orang Tua
                'nama_ayah' => 'required|string|max:255',
                'pekerjaan_ayah' => 'nullable|string|max:100',
                'no_hp_ayah' => 'nullable|string|max:20',
                'nama_ibu' => 'required|string|max:255',
                'pekerjaan_ibu' => 'nullable|string|max:100',
                'no_hp_ibu' => 'nullable|string|max:20',
                'nama_wali' => 'nullable|string|max:255',
                'pekerjaan_wali' => 'nullable|string|max:100',
                'no_hp_wali' => 'nullable|string|max:20',

                // Dokumen - Kompetensi Pemrograman Multimedia
                'foto_siswa' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'akta_kelahiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
                'kartu_keluarga' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
                'ijazah_tk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            ], [
                // Custom error messages dalam Bahasa Indonesia
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'nik.required' => 'NIK wajib diisi.',
                'nik.size' => 'NIK harus 16 digit.',
                'nik.unique' => 'NIK sudah terdaftar. Silakan gunakan NIK lain.',
                'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'agama.required' => 'Agama wajib dipilih.',
                'alamat.required' => 'Alamat wajib diisi.',
                'nama_ayah.required' => 'Nama ayah wajib diisi.',
                'nama_ibu.required' => 'Nama ibu wajib diisi.',
                'foto_siswa.image' => 'Foto siswa harus berupa gambar.',
                'foto_siswa.max' => 'Ukuran foto siswa maksimal 2MB.',
                'akta_kelahiran.max' => 'Ukuran akta kelahiran maksimal 5MB.',
                'kartu_keluarga.max' => 'Ukuran kartu keluarga maksimal 5MB.',
                'ijazah_tk.max' => 'Ukuran ijazah TK maksimal 5MB.',
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

            // Generate nomor pendaftaran - Kompetensi Algoritma
            $data['no_pendaftaran'] = Pendaftaran::generateNoPendaftaran();
            $data['tahun_ajaran'] = '2026/2027';
            $data['status'] = 'menunggu';

            // Upload file multimedia - Kompetensi Pemrograman Multimedia
            $uploadFields = ['foto_siswa', 'akta_kelahiran', 'kartu_keluarga', 'ijazah_tk'];
            foreach ($uploadFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = $data['no_pendaftaran'].'_'.$field.'_'.time().'.'.$file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen/'.$data['no_pendaftaran'], $filename, 'public');
                    $data[$field] = $path;

                    Log::info("File {$field} berhasil diupload", [
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $file->getSize(),
                    ]);
                }
            }

            // Simpan ke database - Kompetensi Akses Basis Data
            $pendaftaran = Pendaftaran::create($data);

            Log::info('Pendaftaran berhasil disimpan', [
                'id' => $pendaftaran->id,
                'no_pendaftaran' => $pendaftaran->no_pendaftaran,
                'nama' => $pendaftaran->nama_lengkap,
            ]);

            return redirect()->route('pendaftaran.success', $pendaftaran->no_pendaftaran)
                ->with('success', 'Pendaftaran berhasil! Simpan nomor pendaftaran Anda.');

        } catch (\Exception $e) {
            // Error handling - Kompetensi Debugging
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

    /**
     * Menampilkan halaman sukses pendaftaran.
     *
     * @return View
     */
    public function success(string $noPendaftaran)
    {
        $pendaftaran = Pendaftaran::where('no_pendaftaran', $noPendaftaran)->firstOrFail();

        return view('pendaftaran.success', compact('pendaftaran'));
    }

    /**
     * Menampilkan halaman cetak bukti pendaftaran.
     *
     * @return View
     */
    public function cetak(string $noPendaftaran)
    {
        $pendaftaran = Pendaftaran::where('no_pendaftaran', $noPendaftaran)->firstOrFail();

        return view('pendaftaran.cetak', compact('pendaftaran'));
    }

    /**
     * Cek status pendaftaran berdasarkan nomor pendaftaran.
     *
     * @return View
     */
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
