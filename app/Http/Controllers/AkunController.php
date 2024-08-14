<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AkunController extends Controller
{
    public function index()
    {
        $title = 'My Profile';
        // Mendapatkan data user yang sedang login berdasarkan ID
        $user = User::find(Auth::id());

        // Menampilkan halaman profile dengan data user
        return view('components.profile', compact('user', 'title'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        // Aturan validasi
        $rules = [
            'level' => 'required|string',
            'status' => 'required|string',
            'name' => 'required|string|max:255',
            'nik' => 'required|string|regex:/^[0-9]{5,6}$/',
            'no_hp' => 'required|digits_between:1,15',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ];

        // Tambah aturan validasi untuk password jika checkbox dicentang
        if ($request->has('change_password')) {
            $rules['old_password'] = 'required|string';
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required|string|same:password';
        }

        // Tambah aturan validasi untuk foto profil jika ada
        if ($request->hasFile('foto_profile')) {
            $rules['foto_profile'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'; // Max 2MB
        }

        // Pesan validasi khusus
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa string.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa string.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa string.',
            'password.min' => 'Kata sandi harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.regex' => 'NIK harus berupa angka dengan 5-6 digit.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.digits_between' => 'Nomor HP harus terdiri dari 1 hingga 15 digit.',
            'foto_profile.image' => 'Foto profil harus berupa gambar.',
            'foto_profile.mimes' => 'Foto profil harus berformat jpeg, png, jpg, atau gif.',
            'foto_profile.max' => 'Foto profil tidak boleh lebih dari 2MB.',
        ];

        // Validasi input
        $validatedData = $request->validate($rules, $messages);

        // Mengupdate data user
        $user->level = $validatedData['level'];
        $user->status = $validatedData['status'];
        $user->name = $validatedData['name'];
        $user->nik = $validatedData['nik'];
        $user->no_hp = $validatedData['no_hp'];
        $user->email = $validatedData['email'];

        // Cek apakah ada foto profil baru
        if ($request->hasFile('foto_profile')) {
            // Hapus foto profil lama jika ada
            if ($user->foto_profile && Storage::exists('public/file_fotoprofile/' . $user->foto_profile)) {
                Storage::delete('public/file_fotoprofile/' . $user->foto_profile);
            }

            // Simpan foto profil baru
            $file = $request->file('foto_profile');

            // Buat nama file baru
            $fileName = $validatedData['name'] . '_' . $validatedData['nik'] . '_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/file_fotoprofile', $fileName);

            // Update nama file di database
            $user->foto_profile = $fileName;
        }

        // Jika checkbox untuk mengubah password dicentang
        if ($request->has('change_password')) {
            // Verifikasi password lama
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors(['old_password' => 'Password lama tidak cocok.']);
            }

            // Update password baru
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
