<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class FacilitiWebController extends Controller
{

    public function index()
    {
        $facilities = Facility::where('is_active', 1)
            ->orderBy('id', 'asc')
            ->get();

        return view('frontend.facilities', compact('facilities'));
    }
}
