<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService; // Import Service yang baru dibuat

class AuthController extends Controller
{
    protected $authService;

    // Inject AuthService ke dalam Controller
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // ==================== REGISTER ====================
    public function registerForm()
    {
        return view('auth.register', ['title' => 'Daftar Akun']);
    }

    public function register(Request $request)
    {
        // 1. Controller HANYA bertugas memvalidasi input
        $validatedData = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|in:pembeli,penjual',
            'shop_name' => 'required_if:role,penjual|nullable|string|max:255|unique:users,shop_name',
        ], [
            'shop_name.required_if' => 'Penjual wajib mencantumkan nama toko.',
            'shop_name.unique'      => 'Nama toko sudah digunakan, cari nama lain yang lebih unik.'
        ]);

        // 2. Lempar data yang sudah valid ke Service untuk diproses (dapur)
        $this->authService->registerUser($validatedData);

        // 3. Kembalikan response
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan masuk ke akun Anda.');
    }

    // ==================== LOGIN ====================
    public function loginForm()
    {
        return view('auth.login', ['title' => 'Login']);
    }

    public function login(Request $request)
    {
        // 1. Validasi input kredensial
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Proses Login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 3. Minta Service untuk menentukan arah redirect berdasarkan role
            $redirectPath = $this->authService->getRedirectPath(Auth::user());

            return redirect()->intended($redirectPath);
        }

        // Jika gagal
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    // ==================== LOGOUT ====================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
