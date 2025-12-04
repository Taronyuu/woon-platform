<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('account.consumer');
        }

        return view('auth.verify-email');
    }

    public function verify(Request $request, int $id, string $hash)
    {
        $user = User::query()->findOrFail($id);

        if (! hash_equals(sha1($user->email), $hash)) {
            return redirect()->route('login')->withErrors(['email' => 'Ongeldige verificatielink.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('account.consumer')->with('status', 'Je e-mailadres is al geverifieerd.');
        }

        $user->markEmailAsVerified();

        auth()->login($user);

        return redirect()->route('account.consumer')->with('status', 'Je e-mailadres is succesvol geverifieerd!');
    }

    public function resend(Request $request)
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('account.consumer');
        }

        $this->sendVerificationEmail($user);

        return back()->with('status', 'Er is een nieuwe verificatielink naar je e-mailadres gestuurd.');
    }

    public static function sendVerificationEmail(User $user): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));
    }
}
