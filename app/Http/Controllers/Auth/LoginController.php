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
                if ($user->id == 1) {
                    Auth::login($user);
                    if (Session::has('email')) {
                        Session::forget('email');
                    }

                    return redirect(route('home'));
                }
                $roles = $user->hasRole([config('constants.roles.role_operator'), config('constants.roles.role_administrator')]);
                if ($roles === true && $user->company_id != null) {
                    $company = Company::query()
                        ->where('id', $user->company_id)
                        ->where('status', Company::STATUS_ACTIVE)
                        ->first();
                    if ($company !== null) {
                        Auth::login($user);
                        if (Session::has('email')) {
                            Session::forget('email');
                        }

                        if ($user->hasRole(config('constants.roles.role_administrator'))) {
                            return redirect(route('viewProfileCompany'));
                        }
                        return redirect(route('home'));
                    } else {
                        return redirect(route('login'))->with('inactiveCompany', Lang::get('auth.inactiveCompany'));
                    }

                } else {
                    return redirect(route('login'))->with('notAuthorized', Lang::get('auth.notAuthorized'));
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
