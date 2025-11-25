<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    protected $redirectTo = '/home';

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
       protected function username()
    {
        return 'correo_electronico';
    }

    protected function credentials(Request $request)
    {
        return $request->only('correo_electronico', 'password');
    }
     public function apiLogin(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = \App\Models\User::where('correo_electronico', $request->correo_electronico)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'user' => $user,
            'token' => $token
        ]);
    }

      protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Login exitoso.',
                'user' => Auth::user(),
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }

     protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        return back()->withErrors([
            $this->username() => trans('auth.failed'),
        ]);
    }

}
