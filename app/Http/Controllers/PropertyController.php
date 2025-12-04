<?php

namespace App\Http\Controllers;

use App\Models\PropertyUnit;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = PropertyUnit::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('address_city', 'like', "%{$search}%")
                    ->orWhere('address_street', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('transaction_type', $type);
        }

        if ($minPrice = $request->input('min_price')) {
            $query->where(function ($q) use ($minPrice, $type) {
                if ($type === 'sale') {
                    $q->where('buyprice', '>=', $minPrice);
                } elseif ($type === 'rent') {
                    $q->where('rentprice_month', '>=', $minPrice);
                } else {
                    $q->where(function ($sq) use ($minPrice) {
                        $sq->where('buyprice', '>=', $minPrice)
                            ->orWhere('rentprice_month', '>=', $minPrice);
                    });
                }
            });
        }

        if ($maxPrice = $request->input('max_price')) {
            $query->where(function ($q) use ($maxPrice, $type) {
                if ($type === 'sale') {
                    $q->where('buyprice', '<=', $maxPrice);
                } elseif ($type === 'rent') {
                    $q->where('rentprice_month', '<=', $maxPrice);
                } else {
                    $q->where(function ($sq) use ($maxPrice) {
                        $sq->where('buyprice', '<=', $maxPrice)
                            ->orWhere('rentprice_month', '<=', $maxPrice);
                    });
                }
            });
        }

        if ($minSurface = $request->input('min_surface')) {
            $query->where('surface', '>=', $minSurface);
        }

        if ($maxSurface = $request->input('max_surface')) {
            $query->where('surface', '<=', $maxSurface);
        }

        if ($rooms = $request->input('rooms')) {
            $query->where('bedrooms', '>=', $rooms);
        }

        if ($energyLabel = $request->input('energy_label')) {
            $query->where('energy_label', $energyLabel);
        }

        $sortBy = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');

        $allowedSorts = ['buyprice', 'rentprice_month', 'surface', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $properties = $query->where('status', 'available')
            ->paginate(12);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'html' => view('partials.property-list', ['properties' => $properties])->render(),
                'pagination' => view('partials.pagination', ['properties' => $properties])->render(),
                'total' => $properties->total(),
            ]);
        }

        return view('zoeken', [
            'properties' => $properties,
            'filters' => $request->all(),
        ]);
    }

    public function show(PropertyUnit $property)
    {
        return view('detailpagina', [
            'property' => $property,
        ]);
    }

    public function addToFavorites(PropertyUnit $property)
    {
        $user = auth()->user();

        if (!$user->hasFavorited($property)) {
            $user->favoriteProperties()->attach($property->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Woning toegevoegd aan favorieten',
            'is_favorited' => true,
        ]);
    }

    public function removeFromFavorites(PropertyUnit $property)
    {
        $user = auth()->user();

        $user->favoriteProperties()->detach($property->id);

        return response()->json([
            'success' => true,
            'message' => 'Woning verwijderd uit favorieten',
            'is_favorited' => false,
        ]);
    }
}
