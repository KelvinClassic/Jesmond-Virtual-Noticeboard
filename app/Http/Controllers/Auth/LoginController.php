<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

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
    protected $redirectTo;

    public function redirectTo()
    {
        $this->redirectTo = 'pages.index';

        // Mail::to(Auth::user()->email)->send(new LoginMail(Auth::user()));
        return route($this->redirectTo);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return back()->with('error', 'The provided credentials do not match our records.');
        }
        // Check if the email has been verified
        if (is_null($user->email_verified_at)) {
            $token = Str::uuid();
            Notification::route('mail', $user->email)
                ->notify((new EmailVerificationNotification($user, $token))
                    ->delay(now()->addSeconds(1))->onQueue('default'));
            EmailVerification::create(['user_id' => $user->id, 'token' => Hash::make($token)]);
            return back()->with('success', 'Verify your email. Email verification link has been sent to your email address');
        }
        if (Hash::check($request->input('password'), $user->password)) {
            Auth::login($user);
            return $this->sendLoginResponse($request);
        }
        return back()->with('error', 'The provided credentials do not match our records.');
    }
}
