<?php

namespace App\Http\Controllers;

use App\Models\PropertyInquiry;
use App\Models\PropertyUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PropertyInquiryController extends Controller
{
    public function store(Request $request, PropertyUnit $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        $inquiry = PropertyInquiry::query()->create([
            'property_unit_id' => $property->id,
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
            'agent_email' => $property->agent_email,
        ]);

        if ($property->agent_email) {
            try {
                Mail::send('emails.property-inquiry', [
                    'inquiry' => $inquiry,
                    'property' => $property,
                ], function ($message) use ($property, $inquiry) {
                    $message->to($property->agent_email, $property->agent_name ?? 'Makelaar')
                        ->subject("Nieuwe vraag over {$property->full_address}")
                        ->replyTo($inquiry->email, $inquiry->name);
                });

                $inquiry->update([
                    'sent_to_agent' => true,
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send inquiry email', [
                    'inquiry_id' => $inquiry->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Uw vraag is succesvol verstuurd!',
        ]);
    }
}
