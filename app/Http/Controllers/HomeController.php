<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
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
