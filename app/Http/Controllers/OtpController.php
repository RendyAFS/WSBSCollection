<?php

namespace App\Http\Controllers;

use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;



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
}
