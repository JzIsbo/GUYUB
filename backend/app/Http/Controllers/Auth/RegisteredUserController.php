<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nomor_kk' => ['required', 'numeric', 'digits:16'],
            'status_warga' => ['required', 'in:Tetap,Kontrak,Kos'],
            'blok_rumah' => ['required', 'string', 'max:255'],
            'umur' => ['required', 'integer', 'min:1', 'max:120'],
            'status_keluarga' => ['required', 'in:Kepala Keluarga,Istri,Anak,Lainnya'],
            'foto_ktp' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // Upload foto KTP
        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ktp'), $filename);
            $fotoKtpPath = 'uploads/ktp/' . $filename;
        }

        // 1. Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Warga',
            'status' => 'Pending',
        ]);

        // 2. Generate random unique NIK
        $nik = '3275' . str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
        while (\Illuminate\Support\Facades\DB::table('wargas')->where('nik', $nik)->exists()) {
            $nik = '3275' . str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
        }

        // 3. Create Warga record linked by name
        \Illuminate\Support\Facades\DB::table('wargas')->insert([
            'nik' => $nik,
            'nomor_kk' => $request->nomor_kk,
            'nama_lengkap' => $request->name,
            'blok_rumah' => $request->blok_rumah,
            'status_keluarga' => $request->status_keluarga,
            'status_domisili' => $request->status_warga,
            'umur' => $request->umur,
            'foto_ktp' => $fotoKtpPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('success_message', 'Pendaftaran warga baru berhasil! Akun Anda sedang menunggu persetujuan (approval) dari pengurus RT sebelum dapat masuk ke sistem.');
    }
}
