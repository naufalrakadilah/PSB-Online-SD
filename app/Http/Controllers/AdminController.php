<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * AdminController - Controller untuk panel admin PSB Online.
 * 
 * Kompetensi:
 * - J.620100.017.02 - Mengimplementasikan Pemrograman Terstruktur
 * - J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek
 * - J.620100.020.02 - Menggunakan SQL
 * - J.620100.021.02 - Menerapkan Akses Basis Data
 * - J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
 * - J.620100.025.02 - Melakukan Debugging
 * - J.620100.044.01 - Menerapkan Alert Notification
 * - J.620100.045.01 - Melakukan Pemantauan Resource
 */
class AdminController extends Controller
{
    /**
     * Menampilkan halaman login admin.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Proses login admin.
     * 
     * Kompetensi J.620100.025.02 - Debugging & Error Handling
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                Log::info('Admin berhasil login', [
                    'user' => Auth::user()->email,
                    'ip' => $request->ip(),
                ]);

                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
            }

            Log::warning('Percobaan login gagal', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
            ]);

            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput($request->only('email'));

        } catch (\Exception $e) {
            Log::error('Error saat proses login', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Proses logout admin.
     */
    public function logout(Request $request)
    {
        Log::info('Admin logout', ['user' => Auth::user()->email ?? 'unknown']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Berhasil logout.');
    }

    /**
     * Menampilkan dashboard admin dengan statistik.
     * 
     * Kompetensi:
     * - J.620100.020.02 - Menggunakan SQL (aggregate queries)
     * - J.620100.022.02 - Algoritma penghitungan statistik
     * - J.620100.045.01 - Pemantauan Resource
     */
    public function dashboard()
    {
        try {
            // Statistik menggunakan raw SQL - Kompetensi SQL
            $stats = DB::select("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as menunggu,
                    SUM(CASE WHEN status = 'diverifikasi' THEN 1 ELSE 0 END) as diverifikasi,
                    SUM(CASE WHEN status = 'diterima' THEN 1 ELSE 0 END) as diterima,
                    SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
                FROM pendaftaran 
                WHERE tahun_ajaran = ?
            ", ['2026/2027']);

            $statistik = $stats[0];

            // Statistik gender - Kompetensi SQL
            $genderStats = DB::select("
                SELECT 
                    jenis_kelamin,
                    COUNT(*) as jumlah
                FROM pendaftaran 
                WHERE tahun_ajaran = ?
                GROUP BY jenis_kelamin
            ", ['2026/2027']);

            // Pendaftar terbaru
            $pendaftarTerbaru = Pendaftaran::orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Statistik pendaftaran per hari (7 hari terakhir) - Kompetensi Algoritma
            $chartData = DB::select("
                SELECT 
                    DATE(created_at) as tanggal,
                    COUNT(*) as jumlah
                FROM pendaftaran 
                WHERE created_at >= date('now', '-7 days')
                GROUP BY DATE(created_at)
                ORDER BY tanggal ASC
            ");

            // Resource monitoring - Kompetensi Pemantauan Resource
            $resourceInfo = $this->getResourceInfo();

            return view('admin.dashboard', compact(
                'statistik',
                'genderStats',
                'pendaftarTerbaru',
                'chartData',
                'resourceInfo'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading dashboard', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Alert notification - Kompetensi Alert Notification
            return view('admin.dashboard', [
                'statistik' => (object) ['total' => 0, 'menunggu' => 0, 'diverifikasi' => 0, 'diterima' => 0, 'ditolak' => 0],
                'genderStats' => [],
                'pendaftarTerbaru' => collect(),
                'chartData' => [],
                'resourceInfo' => $this->getResourceInfo(),
                'alertError' => 'Terjadi kesalahan saat memuat data dashboard: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Menampilkan daftar pendaftar dengan filter dan pencarian.
     * 
     * Kompetensi:
     * - J.620100.020.02 - SQL Query dengan filter
     * - J.620100.022.02 - Algoritma sorting dan filtering
     */
    public function pendaftar(Request $request)
    {
        try {
            $query = Pendaftaran::query();

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }

            // Pencarian
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Sorting - Kompetensi Algoritma
            $sortBy = $request->input('sort', 'created_at');
            $sortDir = $request->input('dir', 'desc');
            $allowedSorts = ['created_at', 'nama_lengkap', 'no_pendaftaran', 'status'];

            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
            }

            $pendaftar = $query->paginate(15)->withQueryString();

            return view('admin.pendaftar', compact('pendaftar'));

        } catch (\Exception $e) {
            Log::error('Error loading pendaftar list', ['message' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat data pendaftar.');
        }
    }

    /**
     * Menampilkan detail pendaftar.
     */
    public function detail(int $id)
    {
        try {
            $pendaftaran = Pendaftaran::findOrFail($id);
            return view('admin.detail', compact('pendaftaran'));
        } catch (\Exception $e) {
            Log::error('Error loading detail pendaftar', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Data pendaftar tidak ditemukan.');
        }
    }

    /**
     * Update status pendaftar (verifikasi / terima / tolak).
     * 
     * Kompetensi J.620100.021.02 - Akses Basis Data (Update operation)
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diverifikasi,diterima,ditolak',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        try {
            $pendaftaran = Pendaftaran::findOrFail($id);
            $oldStatus = $pendaftaran->status;

            $pendaftaran->update([
                'status' => $request->status,
                'catatan_admin' => $request->catatan_admin,
            ]);

            Log::info('Status pendaftar diupdate', [
                'id' => $id,
                'nama' => $pendaftaran->nama_lengkap,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'admin' => Auth::user()->name,
            ]);

            $statusLabels = [
                'menunggu' => 'Menunggu',
                'diverifikasi' => 'Diverifikasi',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak',
            ];

            return back()->with('success', "Status berhasil diubah menjadi '{$statusLabels[$request->status]}'.");

        } catch (\Exception $e) {
            Log::error('Error updating status', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengupdate status pendaftar.');
        }
    }

    /**
     * Hapus data pendaftar.
     */
    public function destroy(int $id)
    {
        try {
            $pendaftaran = Pendaftaran::findOrFail($id);
            $nama = $pendaftaran->nama_lengkap;

            // Hapus file yang terkait
            $fileFields = ['foto_siswa', 'akta_kelahiran', 'kartu_keluarga', 'ijazah_tk'];
            foreach ($fileFields as $field) {
                if ($pendaftaran->$field) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($pendaftaran->$field);
                }
            }

            $pendaftaran->delete();

            Log::info('Pendaftar dihapus', [
                'id' => $id,
                'nama' => $nama,
                'admin' => Auth::user()->name,
            ]);

            return redirect()->route('admin.pendaftar')
                ->with('success', "Data pendaftar '{$nama}' berhasil dihapus.");

        } catch (\Exception $e) {
            Log::error('Error deleting pendaftar', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Gagal menghapus data pendaftar.');
        }
    }

    /**
     * Mengambil informasi resource sistem.
     * 
     * Kompetensi J.620100.045.01 - Melakukan Pemantauan Resource yang Digunakan Aplikasi
     *
     * @return array
     */
    private function getResourceInfo(): array
    {
        try {
            $dbSize = 0;
            $dbPath = database_path('database.sqlite');
            if (file_exists($dbPath)) {
                $dbSize = filesize($dbPath);
            }

            $storageSize = 0;
            $storagePath = storage_path('app/public/dokumen');
            if (is_dir($storagePath)) {
                $storageSize = $this->getDirectorySize($storagePath);
            }

            return [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'db_size' => $this->formatBytes($dbSize),
                'storage_size' => $this->formatBytes($storageSize),
                'memory_usage' => $this->formatBytes(memory_get_usage(true)),
                'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
                'disk_free' => $this->formatBytes(disk_free_space('.')),
                'server_time' => now()->format('d/m/Y H:i:s'),
                'uptime' => $this->getServerUptime(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting resource info', ['message' => $e->getMessage()]);
            return [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'error' => 'Gagal memuat informasi resource',
            ];
        }
    }

    /**
     * Menghitung ukuran direktori secara rekursif.
     * 
     * Kompetensi J.620100.022.02 - Algoritma rekursif
     */
    private function getDirectorySize(string $path): int
    {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    /**
     * Format bytes ke human-readable string.
     */
    private function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Mendapatkan server uptime.
     */
    private function getServerUptime(): string
    {
        try {
            if (PHP_OS_FAMILY === 'Darwin' || PHP_OS_FAMILY === 'Linux') {
                $uptime = shell_exec('uptime -p 2>/dev/null') ?: shell_exec('uptime');
                return trim($uptime ?? 'N/A');
            }
            return 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
