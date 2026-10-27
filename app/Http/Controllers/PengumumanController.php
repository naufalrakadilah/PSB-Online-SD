<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Pendaftaran::whereIn('status', ['diterima', 'ditolak'])
                ->where('tahun_ajaran', '2026/2027');

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                        ->orWhere('no_pendaftaran', 'like', '%'.$request->search.'%');
                });
            }

            $hasil = $query->orderBy('status', 'asc')
                ->orderBy('nama_lengkap', 'asc')
                ->paginate(20)
                ->withQueryString();

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
