<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    }

    public function loginCheck() {
        $name = \request()->name;
        $user = User::where('name', '=', $name)->first();
        $password = Hash::check(\request()->password, $user->password) ;
        if($password) {
            $this->guard()->login($user);
            return view('dashboard.app');
        } else {
            return view("auth.login");
        }
    }

}
