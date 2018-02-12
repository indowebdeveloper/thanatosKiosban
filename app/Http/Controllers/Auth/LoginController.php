<?php

namespace Thanatos\Http\Controllers\Auth;

use Thanatos\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = "/";
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = '/'.config("app.admin_url");
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function logout(Request $request)
	{
		$this->guard()->logout();

		$request->session()->invalidate();

		return redirect('/'.config("app.admin_url"));
	}
}
