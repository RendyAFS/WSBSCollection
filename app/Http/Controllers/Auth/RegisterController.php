<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpCodeMail;
use App\Models\User;
use App\Models\OtpCode; // Pastikan OtpCode diimport
use Illuminate\Support\Str; // Untuk generate kode OTP
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
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
            'nik.regex' => 'NIK harus berupa angka dan memiliki panjang 5-6 digit.',
            'nik.min' => 'NIK harus minimal 5 digit.',
            'nik.max' => 'NIK tidak boleh lebih dari 6 digit.',
            'no_hp.required' => 'Nomor telepon wajib diisi.',
            'no_hp.digits_between' => 'Nomor telepon harus terdiri dari 1 hingga 15 digit angka.',
        ];

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'regex:/^\d{5,6}$/'],
            'no_hp' => ['required', 'digits_between:1,15'], // Validasi untuk no_hp dengan panjang 1-15 digit
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], $messages);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Simpan data pengguna
        $user = User::create([
            'level' => $data['level'],
            'status' => $data['status'],
            'name' => $data['name'],
            'nik' => $data['nik'],
            'no_hp' => $data['no_hp'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Simpan ID pengguna di session
        session()->put('user_id', $user->id);

        // Generate kode OTP 6 digit
        $otpCode = rand(100000, 999999);

        // Simpan data OTP
        OtpCode::create([
            'email' => $data['email'],
            'kode_otp' => $otpCode,
        ]);

        // Kirim email dengan OTP
        Mail::to($data['email'])->send(new OtpCodeMail($otpCode));

        return $user;
    }
}
