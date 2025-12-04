<?php

namespace App\Http\Controllers;

use App\Models\SearchProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchProfileController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        if ($user->searchProfiles()->count() >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Je kunt maximaal 5 zoekprofielen aanmaken.',
            ], 422);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'transaction_type' => 'nullable|string|in:sale,rent',
            'cities' => 'nullable|array',
            'cities.*' => 'string',
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0',
            'min_surface' => 'nullable|integer|min:0',
            'max_surface' => 'nullable|integer|min:0',
            'min_bedrooms' => 'nullable|integer|min:0',
            'property_type' => 'nullable|string',
            'energy_label' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Geef je zoekprofiel een naam.',
            'name.max' => 'De naam mag maximaal 255 tekens bevatten.',
        ]);

        $profile = $user->searchProfiles()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Zoekprofiel aangemaakt.',
            'profile' => $profile,
        ]);
    }

    public function update(Request $request, SearchProfile $searchProfile): JsonResponse
    {
        if ($searchProfile->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Niet geautoriseerd.',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'transaction_type' => 'nullable|string|in:sale,rent',
            'cities' => 'nullable|array',
            'cities.*' => 'string',
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0',
            'min_surface' => 'nullable|integer|min:0',
            'max_surface' => 'nullable|integer|min:0',
            'min_bedrooms' => 'nullable|integer|min:0',
            'property_type' => 'nullable|string',
            'energy_label' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $searchProfile->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Zoekprofiel bijgewerkt.',
            'profile' => $searchProfile->fresh(),
        ]);
    }

    public function destroy(SearchProfile $searchProfile): JsonResponse
    {
        if ($searchProfile->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Niet geautoriseerd.',
            ], 403);
        }

        $searchProfile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Zoekprofiel verwijderd.',
        ]);
    }

    public function toggleActive(SearchProfile $searchProfile): JsonResponse
    {
        if ($searchProfile->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Niet geautoriseerd.',
            ], 403);
        }

        $searchProfile->update([
            'is_active' => ! $searchProfile->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => $searchProfile->is_active ? 'Zoekprofiel geactiveerd.' : 'Zoekprofiel gedeactiveerd.',
            'is_active' => $searchProfile->is_active,
        ]);
    }
}
