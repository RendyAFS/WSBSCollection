<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->level === 'Super Admin' && $user->status === 'Aktif') {
                return redirect()->route('super-admin.index');
            } elseif ($user->level === 'Admin Billper' && $user->status === 'Aktif') {
                return redirect()->route('adminbillper.index');
            } elseif ($user->level === 'Admin PraNPC' && $user->status === 'Aktif') {
                return redirect()->route('adminpranpc.index');
            } elseif ($user->level === 'User' && $user->status === 'Aktif') {
                return redirect()->route('user.index');
            }
        }

        return back()->withErrors([
            'email' => 'Email Tidak Terdaftar/Salah',
            'password' => 'Password yang dimasukkan salah',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
