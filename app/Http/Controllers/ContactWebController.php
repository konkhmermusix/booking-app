<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactWebController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }
}
