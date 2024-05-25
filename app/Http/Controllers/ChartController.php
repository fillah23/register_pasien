<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller

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
    public function dailyReport(Request $request)
    {
        $startDate = $request->query('startDate', date('Y-m-d'));
        $endDate = $request->query('endDate', date('Y-m-d'));

        $newPatientsCount = DB::table('new_patients')
            ->where(DB::raw('DATE(created_at)'), [$startDate])
            ->count();

        $oldPatientsCount = DB::table('old_patients')
            ->where(DB::raw('DATE(created_at)'), [$startDate])
            ->count();

        $totalPatientsCount = $newPatientsCount + $oldPatientsCount;

        if ($request->ajax()) {
            return response()->json([
                'newPatientsCount' => $newPatientsCount,
                'oldPatientsCount' => $oldPatientsCount,
                'totalPatientsCount' => $totalPatientsCount,
            ]);
        }

        return view('chart.harian', compact('newPatientsCount', 'oldPatientsCount', 'totalPatientsCount'));
    }
    public function weeklyReport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $data = DB::select("
            SELECT 
                DATE(visit_date) AS created_date,
                SUM(new_patient_count) AS new_patient_count,
                SUM(old_patient_count) AS old_patient_count
            FROM (
                SELECT 
                    DATE(created_at) AS visit_date,
                    COUNT(id) AS new_patient_count,
                    0 AS old_patient_count
                FROM 
                    new_patients
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    DATE(created_at)

                UNION ALL

                SELECT 
                    DATE(created_at) AS visit_date,
                    0 AS new_patient_count,
                    COUNT(id) AS old_patient_count
                FROM 
                    old_patients
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    DATE(created_at)
            ) AS combined_data
            GROUP BY 
                created_date;
        ", [$startDate, $endDate, $startDate, $endDate]);

        $labels = [];
        $new_patients = [];
        $old_patients = [];
        $total_patients = [];

        foreach ($data as $item) {
            array_push($labels, $item->created_date);
            array_push($new_patients, $item->new_patient_count);
            array_push($old_patients, $item->old_patient_count);
            array_push($total_patients, $item->new_patient_count + $item->old_patient_count);
        }

        return response()->json([
            'labels' => $labels,
            'new_patients' => $new_patients,
            'old_patients' => $old_patients,
            'total_patients' => $total_patients
        ]);
    }

    public function indexMingguan()
    {
        // Mengambil data awal untuk tampilan awal halaman
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');

        $data = DB::select("
            SELECT 
                DATE(visit_date) AS created_date,
                SUM(new_patient_count) AS new_patient_count,
                SUM(old_patient_count) AS old_patient_count
            FROM (
                SELECT 
                    DATE(created_at) AS visit_date,
                    COUNT(id) AS new_patient_count,
                    0 AS old_patient_count
                FROM 
                    new_patients
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    DATE(created_at)

                UNION ALL

                SELECT 
                    DATE(created_at) AS visit_date,
                    0 AS new_patient_count,
                    COUNT(id) AS old_patient_count
                FROM 
                    old_patients
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    DATE(created_at)
            ) AS combined_data
            GROUP BY 
                created_date;
        ", [$startDate, $endDate, $startDate, $endDate]);

        $labels = [];
        $new_patients = [];
        $old_patients = [];
        $total_patients = [];

        foreach ($data as $item) {
            array_push($labels, $item->created_date);
            array_push($new_patients, $item->new_patient_count);
            array_push($old_patients, $item->old_patient_count);
            array_push($total_patients, $item->new_patient_count + $item->old_patient_count);
        }


        return view('chart.mingguan', [
            'labels' => $labels,
            'new_patients' => $new_patients,
            'old_patients' => $old_patients,
            'total_patients' => $total_patients
        ]);
    }
    public function indexBulanan(Request $request)
    {
        $filterMonth = $request->input('filter_month', date('Y-m'));

        return view('chart.bulanan', compact('filterMonth'));
    }

    public function monthlyReport(Request $request)
    {
        $filterMonth = $request->input('filter_month');
        list($year, $month) = explode('-', $filterMonth);

        $data = DB::select("
            SELECT 
                CASE
                    WHEN DAY(created_at) BETWEEN 1 AND 7 THEN 1
                    WHEN DAY(created_at) BETWEEN 8 AND 14 THEN 2
                    WHEN DAY(created_at) BETWEEN 15 AND 21 THEN 3
                    WHEN DAY(created_at) BETWEEN 22 AND 28 THEN 4
                    WHEN DAY(created_at) >= 29 THEN 5
                END AS week_number,
                SUM(new_patient_count) AS new_patient_count,
                SUM(old_patient_count) AS old_patient_count
            FROM (
                SELECT 
                    created_at,
                    COUNT(id) AS new_patient_count,
                    0 AS old_patient_count
                FROM 
                    new_patients
                WHERE 
                    MONTH(created_at) = ? AND YEAR(created_at) = ?
                GROUP BY 
                    created_at

                UNION ALL

                SELECT 
                    created_at,
                    0 AS new_patient_count,
                    COUNT(id) AS old_patient_count
                FROM 
                    old_patients
                WHERE 
                    MONTH(created_at) = ? AND YEAR(created_at) = ?
                GROUP BY 
                    created_at
            ) AS combined_data
            GROUP BY 
                week_number
            ORDER BY 
                week_number;
        ", [$month, $year, $month, $year]);

        $labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'];
        $new_patients = [0, 0, 0, 0, 0];
        $old_patients = [0, 0, 0, 0, 0];
        $total_patients = [0, 0, 0, 0, 0];

        foreach ($data as $item) {
            $index = $item->week_number - 1;
            $new_patients[$index] = $item->new_patient_count;
            $old_patients[$index] = $item->old_patient_count;
            $total_patients[$index] = $item->new_patient_count + $item->old_patient_count;
        }

        return response()->json([
            'labels' => $labels,
            'new_patients' => $new_patients,
            'old_patients' => $old_patients,
            'total_patients' => $total_patients
        ]);
    }
    public function indexTahunan(Request $request)
    {
        $filterYear = $request->input('filter_year', date('Y'));

        return view('chart.tahunan', compact('filterYear'));
    }

    public function yearlyReport(Request $request)
    {
        $filterYear = $request->input('filter_year');

        $data = DB::select("
            SELECT 
                created_month, 
                SUM(new_patient_count) AS new_patient_count, 
                SUM(old_patient_count) AS old_patient_count 
            FROM (
                SELECT 
                    DATE_FORMAT(np.created_at, '%M') AS created_month, 
                    COUNT(np.id) AS new_patient_count, 
                    0 AS old_patient_count 
                FROM 
                    new_patients np 
                WHERE 
                    YEAR(np.created_at) = ? 
                GROUP BY 
                    DATE_FORMAT(np.created_at, '%M') 

                UNION ALL 

                SELECT 
                    DATE_FORMAT(op.created_at, '%M') AS created_month, 
                    0 AS new_patient_count, 
                    COUNT(op.id) AS old_patient_count 
                FROM 
                    old_patients op 
                WHERE 
                    YEAR(op.created_at) = ? 
                GROUP BY 
                    DATE_FORMAT(op.created_at, '%M') 
            ) AS combined_data 
            GROUP BY 
                created_month 
            ORDER BY 
                STR_TO_DATE(created_month, '%M')
        ", [$filterYear, $filterYear]);

        $labels = [];
        $new_patients = [];
        $old_patients = [];
        $total_patients = [];

        foreach ($data as $item) {
            $labels[] = $item->created_month;
            $new_patients[] = $item->new_patient_count;
            $old_patients[] = $item->old_patient_count;
            $total_patients[] = $item->new_patient_count + $item->old_patient_count;
        }

        return response()->json([
            'labels' => $labels,
            'new_patients' => $new_patients,
            'old_patients' => $old_patients,
            'total_patients' => $total_patients
        ]);
    }
}
