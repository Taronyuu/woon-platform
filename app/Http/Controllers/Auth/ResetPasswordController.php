<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function show(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'Vul een geldig e-mailadres in.',
            'password.required' => 'Wachtwoord is verplicht.',
            'password.min' => 'Je wachtwoord moet minimaal 8 tekens bevatten.',
            'password.confirmed' => 'De wachtwoorden komen niet overeen.',
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $resetRecord) {
            return back()->withErrors(['email' => 'Deze link is ongeldig of verlopen.']);
        }

        if (! hash_equals($resetRecord->token, hash('sha256', $request->token))) {
            return back()->withErrors(['email' => 'Deze link is ongeldig of verlopen.']);
        }

        $tokenAge = \Carbon\Carbon::parse($resetRecord->created_at)->diffInMinutes(now());
        if ($tokenAge > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors(['email' => 'Deze link is verlopen. Vraag een nieuwe aan.']);
        }

        $user = User::query()->where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Geen account gevonden met dit e-mailadres.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Je wachtwoord is gewijzigd. Je kunt nu inloggen.');
    }
}
