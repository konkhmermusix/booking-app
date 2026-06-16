<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Dashboard1Controller extends Controller
{

    public function index()
    {
        return view('admin.dashboard1');
    }
}
