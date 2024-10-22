<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Redirect;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->only('showVerificationNotice');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function showVerificationNotice()
    {
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_token' => 'required|string',
        ]);
    
        $user = User::where('verification_token', $request->verification_token)->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->verification_token = null; 
            $user->save();

            return redirect($this->redirectTo)->with('success', 'Email verified successfully!');
        }

        return redirect('verification.notice')->with('error', 'Invalid verification token.');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        $user = User::where('email', $request->email)->first();

        $token = Str::random(10);
        $user->verification_token = $token;
        $user->save();
    
        Mail::to($user->email)->send(new VerifyEmail(['user' => $user, 'verification_token' => $token]));
    
        return back()->with('resent', 'A new verification link has been sent to your email address.');
    }
}
