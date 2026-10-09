<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class RegionController extends Controller
{
    public function regencies(Province $province)
    {
        return response()->json(
            $province->regencies()->orderBy('name')->get(['id','name'])
        );
    }

    public function districts(Regency $regency)
    {
        return response()->json(
            $regency->districts()->orderBy('name')->get(['id','name'])
        );
    }

    public function villages(District $district)
    {
        return response()->json(
            $district->villages()->orderBy('name')->get(['id','name'])
        );
    }
}