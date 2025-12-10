<?php

namespace App\Http\Controllers;

use App\Models\RealtorAppointment;
use Illuminate\Http\Request;

class RealtorAppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        RealtorAppointment::create($validated);

        return redirect(url()->previous() . '#makelaar-afspraak')->with('appointment_success', true);
    }
}
