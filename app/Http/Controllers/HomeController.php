<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $newPatientsCount = DB::table('new_patients')->count();
        $oldPatientsCount = DB::table('old_patients')->count();
        $totalPatientsCount = $newPatientsCount + $oldPatientsCount;

        return view('home', [
            'newPatientsCount' => $newPatientsCount,
            'oldPatientsCount' => $oldPatientsCount,
            'totalPatientsCount' => $totalPatientsCount,
        ]);
    }
}
