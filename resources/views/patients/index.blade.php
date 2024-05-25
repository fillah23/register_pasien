{{-- resources/views/patients/index.blade.php --}}

@extends('layout.app')
{{-- @extends('adminlte.layouts.app') --}}
@section('title', 'Patients List')

@section('content_header')
<center>
    <h1>Laporan Harian</h1>
</center>
@stop

@section('content')
<div class="container">
    <div class="row mb-3">
        <form method="GET" action="/patients/filter" class="col-md-8">
            <div class="row">
                <div class="col-md-5">
                    <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date">
                </div>
                <div class="col-md-5">
                    <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-block" id="filter-date">Filter</button>
                </div>
            </div>
        </form>
        <div class="col-md-4">
            <button class="btn btn-success btn-block" id="print-table">Print</button>
        </div>
    </div>
    

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Patients Table</h3>
        </div>

        <div class="card-body table-responsive">
            <table id="patients-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>No Reka Medis</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Wilayah</th>
                        <th>Pekerjaan</th>
                        <th>Nama KK</th>
                        <th>Pekerjaan KK</th>
                        <th>Keluhan</th>
                        <th>Poli</th>
                        <th>Jenis Pasien</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr>
                        <td>{{ $patient->nik }}</td>
                        <td>{{ $patient->norm }}</td>
                        <td>{{ $patient->name }}</td>
                        <td>{{ Carbon\Carbon::parse($patient->old_patient_created_at)->format('Y-m-d') ?? Carbon\Carbon::parse($patient->new_patient_created_at)->format('Y-m-d') }}</td>
                        <td>{{ $patient->date_of_birth }}</td>
                        <td>{{ $patient->gender }}</td>
                        <td>{{ $patient->address }}</td>
                        <td>{{ $patient->occupation }}</td>
                        <td>{{ $patient->kk_name }}</td>
                        <td>{{ $patient->occupation }}</td>
                        <td>{{ $patient->old_patient_complaint ?? $patient->new_patient_complaint }}</td>
                        <td>{{ $patient->old_patient_poli ?? $patient->new_patient_poli }}</td>
                        <td>{{ $patient->old_patient_type ?? $patient->new_patient_type }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $patients->links() }}
        </div>
    </div>

</div>
<style>
    /* Mengatur ukuran tanda panah pada tombol pagination */
    svg {
        width: 1.25rem; /* Sesuaikan dengan ukuran kelas w-5 (1.25rem) */
        height: 1.25rem; /* Sesuaikan dengan ukuran kelas h-5 (1.25rem) */
    }
</style>
<script>

    document.getElementById('print-table').addEventListener('click', function () {
        let printContents = document.getElementById('patients-table').outerHTML;
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    });

    const urlParams = new URLSearchParams(window.location.search);
    const start_date = urlParams.get('start_date');
    const end_date = urlParams.get('end_date');


    // Mengisi nilai field-field dengan nilai dari parameter URL
    document.getElementById('start_date').value = start_date;
    document.getElementById('end_date').value = end_date;

</script>

@stop