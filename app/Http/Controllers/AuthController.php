<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        /*
         * Pengguna yang sudah login tidak perlu
         * membuka kembali halaman login.
         */
        if (Auth::check()) {
            return $this->redirectByRole(
                Auth::user()->role
            );
        }

        return view('landing', ['showLoginModal' => true]);
    }

    /**
     * Memproses autentikasi pengguna.
     */
    public function login(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],

            'remember' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Hanya akun berstatus aktif yang
         * dapat melakukan autentikasi.
         *
         * Pastikan nilai pada database memang "aktif",
         * bukan "active" atau nilai lainnya.
         */
        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'status' => 'aktif',
        ];

        $remember = $request->boolean(
            'remember'
        );

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' =>
                    'Email atau kata sandi tidak sesuai, atau akun sedang dinonaktifkan.',
                ])
                ->onlyInput('email');
        }

        /*
         * Mencegah session fixation.
         */
        $request->session()->regenerate();

        $user = Auth::user();

        /*
         * Role tidak dikenal tidak diperbolehkan
         * tetap berada dalam keadaan login.
         */
        if (
            !in_array(
                $user->role,
                [
                    'admin',
                    'guru',
                    'siswa',
                ],
                true
            )
        ) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                    'Role akun tidak dikenali. Hubungi administrator.',
                ]);
        }

        /*
         * Menggunakan redirect biasa, bukan intended.
         *
         * Hal ini mencegah guru diarahkan ke halaman
         * admin apabila sebelumnya mencoba membuka
         * URL admin sebelum login.
         */
        return $this->redirectByRole(
            $user->role
        );
    }

    /**
     * Mengeluarkan pengguna dari sistem.
     */
    public function logout(
        Request $request
    ): RedirectResponse {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login');
    }

    /**
     * Menentukan halaman tujuan berdasarkan role.
     */
    private function redirectByRole(
        string $role
    ): RedirectResponse {
        return match ($role) {
            'admin' => redirect()->route(
                'admin.dashboard'
            ),

            'guru' => redirect()->route(
                'guru.dashboard'
            ),

            'siswa' => redirect()->route(
                'siswa.beranda'
            ),

            default => redirect()->route(
                'login'
            ),
        };
    }
}
