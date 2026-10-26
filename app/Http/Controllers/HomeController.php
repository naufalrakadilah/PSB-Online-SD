<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * HomeController - Controller untuk halaman utama (landing page).
 * 
 * Kompetensi:
 * - J.620100.006.01 - Merancang User Experience
 * - J.620100.017.02 - Mengimplementasikan Pemrograman Terstruktur
 * - J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek
 */
class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page).
     * Menghitung statistik pendaftaran menggunakan query aggregate.
     * 
     * Kompetensi J.620100.020.02 - Menggunakan SQL
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Menggunakan raw SQL untuk statistik (Kompetensi SQL)
            $stats = DB::select("
                SELECT 
                    COUNT(*) as total_pendaftar,
                    SUM(CASE WHEN status = 'diterima' THEN 1 ELSE 0 END) as total_diterima,
                    SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as total_menunggu
                FROM pendaftaran 
                WHERE tahun_ajaran = ?
            ", ['2026/2027']);

            $statistik = $stats[0] ?? (object) [
                'total_pendaftar' => 0,
                'total_diterima' => 0,
                'total_menunggu' => 0,
            ];

            Log::info('Landing page accessed', [
                'total_pendaftar' => $statistik->total_pendaftar,
            ]);

            return view('home', compact('statistik'));
        } catch (\Exception $e) {
            // Kompetensi J.620100.025.02 - Melakukan Debugging
            Log::error('Error loading landing page', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $statistik = (object) [
                'total_pendaftar' => 0,
                'total_diterima' => 0,
                'total_menunggu' => 0,
            ];

            return view('home', compact('statistik'));
        }
    }
}
