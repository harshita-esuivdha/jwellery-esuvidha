<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetalRateController extends Controller
{
public function getRates($date)
{
    $rates = DB::table('metal_rates')->where('rate_date', $date)->first();

    if ($rates) {
        return response()->json([
            'success' => true,
            'gold_24' => $rates->gold_24,
            'gold_22' => $rates->gold_22,
            'gold_18' => $rates->gold_18,
            'silver_999' => $rates->silver_999,
            'silver_925' => $rates->silver_925,
        ]);
    }

    return response()->json(['success' => false]);
}


public function store(Request $request)
{
    $request->validate([
        'rate_date' => 'required|date',
        'gold.24' => 'required|numeric',
        'gold.22' => 'required|numeric',
        'gold.18' => 'required|numeric',
        'silver.999' => 'required|numeric',
        'silver.925' => 'required|numeric',
    ]);

    $exists = DB::table('metal_rates')->where('rate_date', $request->rate_date)->first();

    $data = [
        'gold_24' => $request->gold[24],
        'gold_22' => $request->gold[22],
        'gold_18' => $request->gold[18],
        'silver_999' => $request->silver[999],
        'silver_925' => $request->silver[925],
        'updated_at' => now(),
    ];

    if ($exists) {
        DB::table('metal_rates')->where('rate_date', $request->rate_date)->update($data);
    } else {
        $data['rate_date'] = $request->rate_date;
        $data['created_at'] = now();
        DB::table('metal_rates')->insert($data);
    }

    return redirect()->back()->with('success', 'Daily metal rates saved successfully!');
}

public function fetchRates($date)
{
    $rate = DB::table('metal_rates')->where('rate_date', $date)->first();

    if ($rate) {
        return response()->json([
            'success' => true,
            'gold_24' => $rate->gold_24,
            'gold_22' => $rate->gold_22,
            'gold_18' => $rate->gold_18,
            'silver_999' => $rate->silver_999,
            'silver_925' => $rate->silver_925,
        ]);
    }

    return response()->json(['success' => false]);
}

}
