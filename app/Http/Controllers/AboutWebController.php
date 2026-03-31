<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutHistory;

class AboutWebController extends Controller
{
    public function index()
    {

        // ទាញយកតែទិន្នន័យណាដែល Active (status = 1)
        $histories = AboutHistory::where('status', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // $facilities = Galleries::where('type', 'facility')
        //     ->where('status', true)
        //     ->get();

        return view('frontend.about', compact('histories'));
    }
}
