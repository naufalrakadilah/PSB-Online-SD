<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AkunAdminController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.daftar');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        $user = User::create($validated);

        Log::info('Akun admin baru didaftarkan, menunggu verifikasi', [
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Pendaftaran berhasil. Akun Anda bisa dipakai setelah diverifikasi oleh admin lain.');
    }

    public function index(): View
    {
        $akunMenunggu = User::whereNull('verified_at')->orderBy('created_at')->get();
        $akunAktif = User::with('verifier')->whereNotNull('verified_at')->orderBy('name')->get();

        return view('admin.akun', compact('akunMenunggu', 'akunAktif'));
    }

    public function verify(User $user): RedirectResponse
    {
        if ($user->isVerified()) {
            return back()->with('error', "Akun {$user->name} sudah terverifikasi.");
        }

        $user->verifyBy(Auth::user());

        Log::info('Akun admin diverifikasi', [
            'akun' => $user->email,
            'diverifikasi_oleh' => Auth::user()->email,
        ]);

        return back()->with('success', "Akun {$user->name} berhasil diverifikasi dan sekarang bisa login.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $wasVerified = $user->isVerified();

        if ($wasVerified && User::whereNotNull('verified_at')->count() <= 1) {
            return back()->with('error', 'Akun tidak dapat dihapus. Minimal harus ada 1 admin aktif.');
        }

        $isSelf = $user->is($request->user());
        $user->delete();

        Log::info($wasVerified ? 'Akun admin dihapus' : 'Pendaftaran akun admin ditolak', [
            'akun' => $user->email,
            'oleh' => $request->user()->email,
        ]);

        if ($isSelf) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('success', 'Akun Anda telah dihapus.');
        }

        return back()->with('success', $wasVerified
            ? "Akun admin {$user->name} berhasil dihapus."
            : "Pendaftaran akun {$user->name} ditolak.");
    }
}
