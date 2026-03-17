<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacilitiWebController extends Controller
{
    public function index()
    {
        return view('frontend.facilities');
    }
}
