<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 🔹 Tampilkan form login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    /**
     * 🔹 Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user()->role)
                        ->with('success', 'Berhasil login!');
        }

        return back()->with('error', 'Email atau password salah!')
                     ->withInput($request->only('email'));
    }

    /**
     * 🔹 Logout user
     */
    public function logout(Request $request)
{
    Auth::logout();

    // Hapus session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirect ke login + pesan sukses
    return redirect()->route('login.post')->with('success', 'Berhasil logout!');
}


    /**
     * 🔹 Tampilkan form register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * 🔹 Proses register
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // otomatis user
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    /**
     * 🔹 Redirect berdasarkan role
     */
    private function redirectByRole($role)
    {
        switch ($role) {
            case 'admin':
                // return redirect()->route('admin.dashboard');
            case 'user':
            default:
                return redirect()->route('produk'); // bisa ubah ke 'produk.index'
        }
    }

public function profile()
{
    $user = Auth::user();
    return view('auth.profile', compact('user'));
}


public function updateProfile(Request $request)
{
   $request->validate([
    'name'      => 'required|string|max:100',
    'email'     => 'required|email|unique:users,email,' . Auth::id(),
    'alamat'    => 'nullable|string|max:255',
    'latitude'  => 'nullable|numeric',
    'longitude' => 'nullable|numeric',
    'no_hp'     => 'nullable|string|max:20',
]);


    $user = Auth::user();

    $user->update([
        'name'   => $request->name,
        'email'  => $request->email,
        'alamat' => $request->alamat,
        'latitude'  => $request->latitude,
        'longitude' => $request->longitude,
        'no_hp'  => $request->no_hp,
    ]);

    return back()->with('success', 'Profil berhasil diperbarui!');
}


}
