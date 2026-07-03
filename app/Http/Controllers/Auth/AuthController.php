<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // admin login
    public function showAdminLogin()
    {
        return view('auth.admin.login');
    }

    // Form Login Siswa
    public function showMahasiswaLogin()
    {
        return view('auth.mahasiswa.login');
    }

    //login admin
    public function loginAdmin(Request $request)
    {
        $user = User::where('nim', $request->nim)
            ->where('role', 'admin')
            ->first();

        if (!$user) {
            return back()->with('error', 'Akun admin tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah');
        }

        Session::put('user_id', $user->id);
        Session::put('role', $user->role);

        return redirect('/admin');
    }

    //login siswa
    public function loginMahasiswa(Request $request)
    {
        $user = User::where('nim', $request->nim)
            ->where('role', 'mahasiswa')
            ->first();

        if (!$user) {
            return back()->with('error', 'Akun siswa tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah');
        }

        Session::put('user_id', $user->id);
        Session::put('role', $user->role);

        return redirect()->route('mahasiswa.dashboard');
    }

    public function logout()
    {
        Session::flush();

    
        return redirect()->route('auth.mahasiswa.login');
    }
}
