<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MortgageCalculatorController extends Controller
{
    public function index()
    {
        return view('mortgage-calculator');
    }

    public function getInterestRate(Request $request)
    {
        $product = $request->input('product', 'BUDGET');
        $type = $request->input('type', 'ANNUITAIR');

        $apiType = $type === 'LINEAR' ? 'ANNUITAIR' : $type;

        $cacheKey = "interest_rate_{$product}_{$apiType}";

        try {
            $data = Cache::remember($cacheKey, now()->addDay(), function () use ($product, $apiType) {
                $response = Http::withHeaders([
                    'accept' => 'application/json, text/plain, */*',
                    'accept-language' => 'en-US',
                    'cache-control' => 'no-cache',
                    'content-type' => 'application/json',
                    'origin' => 'https://hypotheken.abnamro.nl',
                    'pragma' => 'no-cache',
                    'referer' => 'https://hypotheken.abnamro.nl/interest-rates/app/?lang=en',
                    'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
                ])
                    ->post('https://hypotheken.abnamro.nl/mortgage-customer-interest-rate-calculation/v1/interest-rates/calculate?inactive=true', [
                        'product' => $product,
                        'type' => $apiType,
                    ]);

                if ($response->successful()) {
                    $rawData = $response->json();

                    $interestRate = 4.15;
                    if (isset($rawData['periods']) && is_array($rawData['periods'])) {
                        foreach ($rawData['periods'] as $period) {
                            if ($period['duration'] == 360 && isset($period['rates'][0]['value'])) {
                                $rateString = $period['rates'][0]['value'];
                                $interestRate = (float) str_replace('%', '', $rateString);
                                break;
                            }
                        }
                    }

                    return [
                        'interestRate' => $interestRate,
                        'rawData' => $rawData,
                    ];
                }

                throw new \Exception('Failed to fetch interest rate');
            });

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
