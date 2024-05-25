<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Pagination\Paginator;
// use Illuminate\Support\Facades\Paginator;

class PatientController extends Controller
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
    public function index()
    {
        $patients = DB::table('patients')
            ->select(
                'patients.*',
                'new_patients.id AS new_patient_id',
                'new_patients.user_id AS new_patient_user_id',
                'new_patients.complaint AS new_patient_complaint',
                'new_patients.poli AS new_patient_poli',
                'new_patients.patient_type AS new_patient_type',
                'new_patients.created_at AS new_patient_created_at',
                'new_patients.updated_at AS new_patient_updated_at',
                DB::raw('NULL AS old_patient_id'),
                DB::raw('NULL AS old_patient_user_id'),
                DB::raw('NULL AS old_patient_complaint'),
                DB::raw('NULL AS old_patient_poli'),
                DB::raw('NULL AS old_patient_type'),
                DB::raw('NULL AS old_patient_created_at'),
                DB::raw('NULL AS old_patient_updated_at')
            )
            ->leftJoin('new_patients', 'patients.id', '=', 'new_patients.patient_id')
            ->unionAll(
                DB::table('patients')
                    ->select(
                        'patients.*',
                        DB::raw('NULL AS new_patient_id'),
                        DB::raw('NULL AS new_patient_user_id'),
                        DB::raw('NULL AS new_patient_complaint'),
                        DB::raw('NULL AS new_patient_poli'),
                        DB::raw('NULL AS new_patient_type'),
                        DB::raw('NULL AS new_patient_created_at'),
                        DB::raw('NULL AS new_patient_updated_at'),
                        'old_patients.id AS old_patient_id',
                        'old_patients.user_id AS old_patient_user_id',
                        'old_patients.complaint AS old_patient_complaint',
                        'old_patients.poli AS old_patient_poli',
                        'old_patients.patient_type AS old_patient_type',
                        'old_patients.created_at AS old_patient_created_at',
                        'old_patients.updated_at AS old_patient_updated_at'
                    )
                    ->leftJoin('old_patients', 'patients.id', '=', 'old_patients.patient_id')
            )
            ->paginate(10); // Ubah angka 10 sesuai dengan jumlah item yang ingin ditampilkan per halaman
    
        return view('patients.index', compact('patients'));
    }
    



    public function show(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        $newPatientsQuery = DB::table('patients')
            ->select(
                'patients.*',
                'new_patients.id AS new_patient_id',
                'new_patients.user_id AS new_patient_user_id',
                'new_patients.complaint AS new_patient_complaint',
                'new_patients.poli AS new_patient_poli',
                'new_patients.patient_type AS new_patient_type',
                'new_patients.created_at AS new_patient_created_at',
                'new_patients.updated_at AS new_patient_updated_at',
                DB::raw('NULL AS old_patient_id'),
                DB::raw('NULL AS old_patient_user_id'),
                DB::raw('NULL AS old_patient_complaint'),
                DB::raw('NULL AS old_patient_poli'),
                DB::raw('NULL AS old_patient_type'),
                DB::raw('NULL AS old_patient_created_at'),
                DB::raw('NULL AS old_patient_updated_at')
            )
            ->leftJoin('new_patients', 'patients.id', '=', 'new_patients.patient_id');
    
        $oldPatientsQuery = DB::table('patients')
            ->select(
                'patients.*',
                DB::raw('NULL AS new_patient_id'),
                DB::raw('NULL AS new_patient_user_id'),
                DB::raw('NULL AS new_patient_complaint'),
                DB::raw('NULL AS new_patient_poli'),
                DB::raw('NULL AS new_patient_type'),
                DB::raw('NULL AS new_patient_created_at'),
                DB::raw('NULL AS new_patient_updated_at'),
                'old_patients.id AS old_patient_id',
                'old_patients.user_id AS old_patient_user_id',
                'old_patients.complaint AS old_patient_complaint',
                'old_patients.poli AS old_patient_poli',
                'old_patients.patient_type AS old_patient_type',
                'old_patients.created_at AS old_patient_created_at',
                'old_patients.updated_at AS old_patient_updated_at'
            )
            ->leftJoin('old_patients', 'patients.id', '=', 'old_patients.patient_id');
    
        $patients = DB::table(DB::raw("({$newPatientsQuery->toSql()} UNION ALL {$oldPatientsQuery->toSql()}) as patients"))
            ->mergeBindings($newPatientsQuery)
            ->mergeBindings($oldPatientsQuery);
    
        if ($startDate && $endDate) {
            $patients->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('new_patient_created_at', [$startDate, $endDate])
                    ->orWhereBetween('old_patient_created_at', [$startDate, $endDate]);
            });
        }
    
        $patients = $patients
            ->selectRaw('*, IFNULL(new_patient_created_at, old_patient_created_at) as created_at')
            ->paginate(10);
    
        return view('patients.index', compact('patients'));
    }
    
    


    public function create()
    {
        return view('patients.create');
    }


    public function store(Request $request)
    {
        // Mengecek apakah NIK atau norm sudah ada dalam database
        $existingPatient = Patient::where('nik', $request->nik)
            ->orWhere('norm', $request->norm)
            ->exists();

        // Jika NIK atau norm sudah ada, redirect ke halaman pasien lama dengan pesan alert
        if ($existingPatient) {
            $params = [
                'nik' => $request->nik,
                'norm' => $request->norm,
                'name' => $request->name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'occupation' => $request->occupation,
                'kk_name' => $request->kk_name,
                'error' => 'NIK/Norm sudah terdaftar.'
            ];

            return redirect()->route('oldpatients.create', $params)->with('alert', 'Pasien sudah terdaftar. silahkan isi form di pasien lama');
        }
        DB::table('patients')->insert([
            'nik' => $request->nik,
            'norm' => $request->norm,
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'occupation' => $request->occupation,
            'kk_name' => $request->kk_name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('new_patients')->insert([
            'user_id' => auth()->id(),
            'patient_id' => DB::table('patients')->where('nik', $request->nik)->value('id'),
            'complaint' => $request->complaint,
            'poli' => $request->poli,
            'patient_type' => $request->patient_type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pasien baru berhasil didaftarkan');
    }

    public function checkPatient(Request $request)
    {
        $nik = $request->query('nik');
        $norm = $request->query('norm');

        $query = DB::table('patients');

        if ($nik) {
            $exists = $query->where('nik', $nik)->exists();
        } elseif ($norm) {
            $exists = $query->where('norm', $norm)->exists();
        }

        return response()->json(['exists' => $exists]);
    }
    // public function show(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     $query = DB::table('patients')
    //         ->leftJoin('new_patients', 'patients.id', '=', 'new_patients.patient_id')
    //         ->leftJoin('old_patients', 'patients.id', '=', 'old_patients.patient_id')
    //         ->select(
    //             'patients.nik',
    //             'patients.norm',
    //             'patients.name',
    //             'patients.date_of_birth',
    //             'patients.gender',
    //             'patients.address',
    //             'patients.occupation',
    //             'patients.kk_name',
    //             'new_patients.complaint as new_patient_complaint',
    //             'new_patients.poli as new_patient_poli',
    //             'new_patients.patient_type as new_patient_type',
    //             'old_patients.complaint as old_patient_complaint',
    //             'old_patients.poli as old_patient_poli',
    //             'old_patients.patient_type as old_patient_type',
    //             'new_patients.created_at as new_patient_created_at',
    //             'old_patients.created_at as old_patient_created_at'
    //         );

    //     if ($startDate && $endDate) {
    //         $query->where(function ($query) use ($startDate, $endDate) {
    //             $query->whereBetween('new_patients.created_at', [$startDate, $endDate])
    //                 ->orWhereBetween('old_patients.created_at', [$startDate, $endDate]);
    //         });
    //     }

    //     $patients = $query->get();

    //     return view('patients.index', compact('patients'));
    // }
}
