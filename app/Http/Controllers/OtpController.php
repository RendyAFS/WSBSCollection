<?php

namespace App\Http\Controllers;

use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class OtpController extends Controller
{
    public function verifyOtp(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'kode_otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email = $request->input('email');
        $kodeOtp = $request->input('kode_otp');

        // Cek apakah OTP cocok dengan email
        $otpCode = OtpCode::where('email', $email)
            ->where('kode_otp', $kodeOtp)
            ->first();

        if ($otpCode) {
            // Ambil ID pengguna dari session atau token
            $userId = $request->session()->get('user_id'); // atau sesuaikan dengan cara Anda menyimpan ID pengguna
            $user = User::find($userId);

            if ($user) {
                $user->update(['email_verified_at' => now()]);
                // Hapus OTP setelah digunakan
                $otpCode->delete();

                return redirect()->route('home')->with('status', 'Email berhasil diverifikasi!');
            } else {
                return redirect()->back()->withErrors(['email' => 'Pengguna tidak ditemukan.'])->withInput();
            }
        } else {
            return redirect()->back()->withErrors(['kode_otp' => 'Kode OTP tidak cocok.'])->withInput();
        }
    }

    public function requestOtp(Request $request)
    {
        // Validasi input email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');

        // Hapus kode OTP lama untuk email yang sama
        OtpCode::where('email', $email)->delete();

        // Generate kode OTP 6 digit
        $otpCode = rand(100000, 999999);

        // Simpan data OTP ke database
        OtpCode::create([
            'email' => $email,
            'kode_otp' => $otpCode,
        ]);

        // Kirim email dengan kode OTP
        Mail::to($email)->send(new OtpCodeMail($otpCode));

        // Return response
        return response()->json(['status' => 'Success']);
    }



    public function indexforgotpassword()
    {
        $title = 'Forgot Password';


        return view('auth.forgot-password', compact('title'));
    }

    public function sendResetLink(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.exists' => 'Email tidak terdaftar di sistem.',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil email dari request
        $email = $request->input('email');

        // Cek apakah email sudah ada di tabel password_resets
        $passwordReset = PasswordReset::where('email', $email)->first();

        // Jika ada, hapus token yang lama
        if ($passwordReset) {
            $passwordReset->delete();
        }

        // Buat token baru
        $token = Str::random(60);

        // Simpan token baru ke tabel password_resets
        PasswordReset::create([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Kirim email reset password
        $resetLink = route('reset-password', ['token' => $token]);
        Mail::send('auth.reset-password-email', ['resetLink' => $resetLink], function ($message) use ($email) {
            $message->to($email)
                ->subject('Reset Password');
        });

        // Redirect dengan pesan sukses
        return redirect()->back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }



    public function indexresetpassword($token)
    {
        // Cek apakah token valid
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset) {
            return redirect()->route('login')
                ->with('success', 'Password Berhasil di Reset.');
        }

        $title = 'Reset Password';
        return view('auth.reset-password', compact('title', 'token'));
    }


    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        // Temukan token
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if (!$passwordReset) {
            return redirect()->route('reset-password', ['token' => $request->token])
                ->with('error', 'Invalid or expired token.');
        }

        // Temukan user berdasarkan email
        $user = User::where('email', $passwordReset->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Hapus token setelah digunakan
        $passwordReset->delete();

        // Redirect dengan pesan sukses dan informasi untuk arahkan ke login
        return redirect()->route('reset-password', ['token' => $request->token])
            ->with('success', 'Password telah berhasil direset.')
            ->with('redirect', '/login');
    }

}
