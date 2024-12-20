<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\Route;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Membuat user baru menggunakan model User::create()
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin', // Sesuaikan role jika diperlukan
            'password' => Hash::make($request->password), // Hash password sebelum disimpan
        ]);


        // Memicu event Registered setelah pembuatan user
        event(new Registered($user));

        // Melakukan login otomatis setelah registrasi
        Auth::login($user);

        // Mengarahkan ke halaman utama setelah login
        return redirect(RouteServiceProvider::HOME);
    }
    public function create()
{
    return view('auth.register'); // Pastikan file view `register` ada
}

}
