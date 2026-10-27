<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * PengumumanController - Controller untuk halaman pengumuman hasil seleksi.
 *
 * Kompetensi:
 * - J.620100.017.02 - Pemrograman Terstruktur
 * - J.620100.018.02 - Pemrograman Berorientasi Objek
 * - J.620100.020.02 - Menggunakan SQL
 * - J.620100.022.02 - Algoritma Pemrograman
 */
class PengumumanController extends Controller
{
    /**
     * Menampilkan halaman pengumuman.
     * Menampilkan daftar siswa yang diterima dan ditolak.
     *
     * Kompetensi:
     * - J.620100.020.02 - SQL query dengan sorting
     * - J.620100.022.02 - Algoritma filtering dan sorting
     */
    public function index(Request $request)
    {
        try {
            // Query siswa yang sudah diproses (diterima/ditolak)
            $query = Pendaftaran::whereIn('status', ['diterima', 'ditolak'])
                ->where('tahun_ajaran', '2026/2027');

            // Pencarian
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                        ->orWhere('no_pendaftaran', 'like', '%'.$request->search.'%');
                });
            }

            // Sorting berdasarkan nama - Kompetensi Algoritma
            $hasil = $query->orderBy('status', 'asc')
                ->orderBy('nama_lengkap', 'asc')
                ->paginate(20)
                ->withQueryString();

            // Statistik pengumuman menggunakan raw SQL
            $stats = DB::select("
                SELECT 
                    SUM(CASE WHEN status = 'diterima' THEN 1 ELSE 0 END) as diterima,
                    SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
                FROM pendaftaran 
                WHERE tahun_ajaran = ? 
                AND status IN ('diterima', 'ditolak')
            ", ['2026/2027']);

            $pengumumanStats = $stats[0] ?? (object) ['diterima' => 0, 'ditolak' => 0];

            return view('pengumuman', compact('hasil', 'pengumumanStats'));

        } catch (\Exception $e) {
            Log::error('Error loading pengumuman', ['message' => $e->getMessage()]);

            return view('pengumuman', [
                'hasil' => collect(),
                'pengumumanStats' => (object) ['diterima' => 0, 'ditolak' => 0],
                'error' => 'Gagal memuat data pengumuman.',
            ]);
        }
    }
}
