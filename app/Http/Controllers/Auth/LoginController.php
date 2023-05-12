<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Auth;
use Lang;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Session;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function login(Request $request)
    {

        $data = Request::all();
        $user = User::query()
            ->where('email', $data['email'])
            ->first();
        if ($user !== null) {
            if (Hash::check($data['password'], $user->password)) {
                Auth::login($user);
                if ($user->id == 1) {
                    
                    if (Session::has('email')) {
                        Session::forget('email');
                    }

                    return redirect(route('home'));
                }
                $rolesProveedor = $user->hasRole(['Proveedor']);
                $rolesEmpresa = $user->hasRole(['Empresa']);

                if ($rolesProveedor) {
                   
                    return redirect(route('home'));
                } 
                if ($rolesEmpresa) {
                   
                    return redirect(route('home'));
                } 
            } else {
                Session::put('email', $data['email']);
                return redirect(route('login'))->with('failedPassword', Lang::get('auth.failedPassword'));
            }
        } else {
            Session::put('email', $data['email']);
            return redirect(route('login'))->with('failedEmail' , Lang::get('auth.failedEmail'));
        }
    }

}
