<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo;

    public function redirectTo()
    {
        $this->redirectTo = 'pages.index';
        return route($this->redirectTo);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('signed')->only('verify');
        //$this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verifyEmail(Request $request)
    {
        $user = User::where(['email' => $request->email])->firstOrFail();
        $emailVerification = EmailVerification::where(['user_id' => $user->id])->orderBy('id', 'desc')->firstOrFail();
        if (Carbon::now()->diffInMinutes(Carbon::parse($emailVerification->created_at)) > 30) {
            abort(419, 'Link has expired');
        }
        if (!empty($emailVerification->used_at)) {
            abort(400, 'Token has been used');
        }
        if (!Hash::check($request->token, $emailVerification->token)) {
            abort(400, 'Invalid Token');
        }
        $user->update(['email_verified_at' => now()]);
        $emailVerification->update(['used_at' => now()]);

        return redirect()->route('login')->with('success','Email verification was successful');
    }
}
