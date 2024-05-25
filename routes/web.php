<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\OldPatientController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');  
Route::get('/oldpatients/create', [OldPatientController::class, 'create'])->name('patients.create');  
Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::get('/check-patient', [PatientController::class, 'checkPatient']);
Route::get('/check-old-patient', [OldPatientController::class, 'checkOldPatient']);
Route::resource('patients', PatientController::class);
Route::get('/patients/filter', [PatientController::class, 'show']);
Route::resource('oldpatients', OldPatientController::class);

// Route::get('/monthly-report', [ReportController::class, 'index'])->name('monthly-report.index');
Route::get('/reports/monthly', [ReportController::class, 'index'])->name('reports.monthly');
Route::resource('users', UserController::class);

Route::get('/chart/harian', [ChartController::class, 'dailyReport'])->name('chart.harian');
Route::get('/chart/mingguan', [ChartController::class, 'indexMingguan']);
Route::post('/chart/mingguan', [ChartController::class, 'weeklyReport']);
Route::get('/chart/bulanan', [ChartController::class, 'indexBulanan']);
Route::post('/chart/bulanan', [ChartController::class, 'monthlyReport']);
Route::get('/chart/tahunan', [ChartController::class, 'indexTahunan']);
Route::post('/chart/tahunan', [ChartController::class, 'yearlyReport']);

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});
