<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeetingWebController extends Controller
{
    public function index()
    {
        return view('frontend.meeting');
    }
}
