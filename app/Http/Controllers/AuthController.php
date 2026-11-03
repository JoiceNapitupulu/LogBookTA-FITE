<?php

namespace App\Http\Controllers;

use App\Models\DosenProfile;
use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->input('username'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // 1. Cek langsung via Email
        $user = User::where('email', $loginInput)->first();

        // 2. Jika bukan email, cek via NIM Mahasiswa
        if (! $user) {
            $mhs = MahasiswaProfile::where('nim', $loginInput)->first();
            if ($mhs) {
                $user = $mhs->user;
            }
        }

        // 3. Jika bukan, cek via NIDN atau Kode Dosen
        if (! $user) {
            $dosen = DosenProfile::where('kode_dosen', $loginInput)
                ->orWhere('nidn', $loginInput)
                ->first();
            if ($dosen) {
                $user = $dosen->user;
            }
        }

        if ($user && Auth::attempt(['email' => $user->email, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang di Workspace Tugas Akhir FITE IT Del, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'username' => 'Username atau kata sandi tidak cocok dengan data institusi FITE.',
        ])->onlyInput('username');
    }

    /**
     * Demo login helper for presentation and testing
     */
    public function demoLogin(string $role): RedirectResponse
    {
        $emailMap = [
            'mahasiswa_ta' => 'mhs.ta@students.del.ac.id',
            'mahasiswa_non_ta' => 'mhs.nonta@students.del.ac.id',
            'dosen' => 'dosen1@del.ac.id',
            'dosen2' => 'dosen2@del.ac.id',
            'kaprodi' => 'kaprodi@del.ac.id',
            'ta' => 'ta@del.ac.id',
            'baak' => 'baak@del.ac.id',
        ];

        $email = $emailMap[$role] ?? 'mhs.ta@students.del.ac.id';
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Beralih peran ke: ' . $user->name . ' (' . strtoupper($user->role) . ')');
        }

        return redirect()->route('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'Anda telah keluar dari Workspace FITE.');
    }
}
