<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginAdminController extends Controller
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

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showAdminLoginForm()
    {
        return view('auth.login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        // if (\Auth::attempt($request->only(['email','password']), $request->get('remember'))){
        if (\Auth::attempt([
                                'email' => $request->email,
                                'password' => $request->password,
                                'role' => 'admin'
                            ],$request->get('remember'))){
            // if(\Auth::user()->role=='admin'){
                return redirect()->route('home');
            // }else{
            //     \Auth::logout();
            //     return back()->withErrors(['error'=>'Admin Login Only']);
            // }
        }else{
            return back()->withErrors(['error'=>'Credentials not match']);
        }
    }
    public function logout(Request $request)
    {
        \Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
