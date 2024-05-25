<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class ReportController extends Controller
{   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
       $this->middleware('auth');
   }
    public function index(Request $request)
    {
        $filterMonth = $request->query('filter_month', date('Y-m'));

        $startOfMonth = date('Y-m-01', strtotime($filterMonth));
        $endOfMonth = date('Y-m-t', strtotime($filterMonth));

        // Jalankan query untuk mendapatkan data laporan bulanan dengan filter bulan dan tahun
        $reportData = collect(DB::select("
            SELECT
                tanggal,
                SUM(CASE WHEN poli = 'Umum' AND patient_type = 'Umum' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'UMUM_UMUM_L',
                SUM(CASE WHEN poli = 'Umum' AND patient_type = 'Umum' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'UMUM_UMUM_P',
                SUM(CASE WHEN poli = 'Umum' AND patient_type = 'BPJS' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'UMUM_BPJS_L',
                SUM(CASE WHEN poli = 'Umum' AND patient_type = 'BPJS' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'UMUM_BPJS_P',
                SUM(CASE WHEN poli = 'TB' AND patient_type = 'Umum' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'TB_UMUM_L',
                SUM(CASE WHEN poli = 'TB' AND patient_type = 'Umum' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'TB_UMUM_P',
                SUM(CASE WHEN poli = 'TB' AND patient_type = 'BPJS' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'TB_BPJS_L',
                SUM(CASE WHEN poli = 'TB' AND patient_type = 'BPJS' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'TB_BPJS_P',
                SUM(CASE WHEN poli = 'IGD' AND patient_type = 'Umum' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'IGD_UMUM_L',
                SUM(CASE WHEN poli = 'IGD' AND patient_type = 'Umum' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'IGD_UMUM_P',
                SUM(CASE WHEN poli = 'IGD' AND patient_type = 'BPJS' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'IGD_BPJS_L',
                SUM(CASE WHEN poli = 'IGD' AND patient_type = 'BPJS' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'IGD_BPJS_P',
                SUM(CASE WHEN poli = 'Inap' AND patient_type = 'Umum' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'Inap_UMUM_L',
                SUM(CASE WHEN poli = 'Inap' AND patient_type = 'Umum' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'Inap_UMUM_P',
                SUM(CASE WHEN poli = 'Inap' AND patient_type = 'BPJS' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'Inap_BPJS_L',
                SUM(CASE WHEN poli = 'Inap' AND patient_type = 'BPJS' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'Inap_BPJS_P',
                SUM(CASE WHEN poli = 'KIA' AND patient_type = 'Umum' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'KIA_UMUM_L',
                SUM(CASE WHEN poli = 'KIA' AND patient_type = 'Umum' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'KIA_UMUM_P',
                SUM(CASE WHEN poli = 'KIA' AND patient_type = 'BPJS' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'KIA_BPJS_L',
                SUM(CASE WHEN poli = 'KIA' AND patient_type = 'BPJS' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'KIA_BPJS_P',
                SUM(CASE WHEN poli = 'Gigi' AND patient_type = 'Umum' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'GIGI_UMUM_L',
                SUM(CASE WHEN poli = 'Gigi' AND patient_type = 'Umum' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'GIGI_UMUM_P',
                SUM(CASE WHEN poli = 'Gigi' AND patient_type = 'BPJS' AND gender = 'L' THEN jumlah ELSE 0 END) AS 'GIGI_BPJS_L',
                SUM(CASE WHEN poli = 'Gigi' AND patient_type = 'BPJS' AND gender = 'P' THEN jumlah ELSE 0 END) AS 'GIGI_BPJS_P',
                SUM(CASE WHEN poli = 'KB' AND patient_type = 'Umum' THEN jumlah ELSE 0 END) AS 'KB_UMUM',
                SUM(CASE WHEN poli = 'KB' AND patient_type = 'BPJS' THEN jumlah ELSE 0 END) AS 'KB_BPJS',
                SUM(CASE WHEN poli = 'VK' AND patient_type = 'Umum' THEN jumlah ELSE 0 END) AS 'VK_UMUM',
                SUM(CASE WHEN poli = 'VK' AND patient_type = 'BPJS' THEN jumlah ELSE 0 END) AS 'VK_BPJS'
            FROM
                (SELECT
                    DATE(new_patients.created_at) AS tanggal,
                    gender,
                    poli,
                    patient_type,
                    COUNT(*) AS jumlah
                FROM
                    new_patients
                JOIN
                    patients ON new_patients.patient_id = patients.id
                WHERE
                    DATE(new_patients.created_at) BETWEEN ? AND ?
                GROUP BY
                    tanggal, gender, poli, patient_type

                UNION ALL

                SELECT
                    DATE(old_patients.created_at) AS tanggal,
                    gender,
                    poli,
                    patient_type,
                    COUNT(*) AS jumlah
                FROM
                    old_patients
                JOIN
                    patients ON old_patients.patient_id = patients.id
                WHERE
                    DATE(old_patients.created_at) BETWEEN ? AND ?
                GROUP BY
                    tanggal, gender, poli, patient_type) AS pivoted_data
            GROUP BY
                tanggal
        ", [$startOfMonth, $endOfMonth, $startOfMonth, $endOfMonth]));

        // Pagination manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10; // jumlah item per halaman
        $currentItems = $reportData->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedItems = new LengthAwarePaginator($currentItems, $reportData->count(), $perPage);
        $paginatedItems->setPath($request->url());

        return view('reports.monthly', ['reportData' => $paginatedItems]);
    }
}
