<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OldPatientController extends Controller
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
    public function create()
    {
        return view('oldPatients.create');
    }

    public function store(Request $request)
    {
        DB::table('old_patients')->insert([
            'user_id' => auth()->id(),
            'patient_id' => DB::table('patients')->where('nik', $request->nik)->value('id'),
            'complaint' => $request->complaint,
            'poli' => $request->poli,
            'patient_type' => $request->patient_type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pasien lama berhasil didaftarkan');
    }

    public function checkOldPatient(Request $request)
{
    $nik = $request->query('nik');
    $norm = $request->query('norm');

    $query = DB::table('patients');

    if ($nik) {
        $patient = $query->where('nik', $nik)->first();
    } elseif ($norm) {
        $patient = $query->where('norm', $norm)->first();
    }

    if ($patient) {
        return response()->json([
            'exists' => true,
            'norm' => $patient->norm,
            'nik' => $patient->nik,
            'name' => $patient->name,
            'date_of_birth' => $patient->date_of_birth,
            'gender' => $patient->gender,
            'address' => $patient->address,
            'occupation' => $patient->occupation,
            'kk_name' => $patient->kk_name,
        ]);
    } else {
        return response()->json(['exists' => false]);
    }
}

}
