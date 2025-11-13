<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        auth()->user()->update($validated);

        return redirect()->back()->with('success', 'Je gegevens zijn succesvol bijgewerkt.');
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'notify_new_properties' => ['nullable', 'boolean'],
            'notify_price_changes' => ['nullable', 'boolean'],
            'notify_newsletter' => ['nullable', 'boolean'],
            'notify_marketing' => ['nullable', 'boolean'],
        ]);

        auth()->user()->update([
            'notify_new_properties' => $request->has('notify_new_properties'),
            'notify_price_changes' => $request->has('notify_price_changes'),
            'notify_newsletter' => $request->has('notify_newsletter'),
            'notify_marketing' => $request->has('notify_marketing'),
        ]);

        return redirect()->back()->with('notification_success', 'Je notificatie-instellingen zijn succesvol bijgewerkt.');
    }
}
