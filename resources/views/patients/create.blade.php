@extends('layout.app')

@section('title', 'Register Patient')

@section('content_header')
<center><h1>Pendaftaran Pasien Baru</h1></center>
@stop

@section('content')
<div class="container">
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}'
                });
            });
        </script>
    @endif

    <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        <div class="form-group row">
            <label for="nik" class="col-sm-2 col-form-label">NIK:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="nik" name="nik" required>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" id="search-nik">Cari</button>
            </div>
        </div>
        <div class="form-group row">
            <label for="nik" class="col-sm-2 col-form-label">No Reka Medis:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="norm" name="norm" required>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" id="search-norm">Cari</button>
            </div>
        </div>
        <div class="form-group">
            <label for="name">Nama:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="date_of_birth">Tanggal Lahir:</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
        </div>
        <div class="form-group">
            <label for="gender">Jenis Kelamin:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="L">Laki - Laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label for="address">Wilayah:</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="occupation">Pekerjaan:</label>
            <input type="text" class="form-control" id="occupation" name="occupation">
        </div>
        <div class="form-group">
            <label for="kk_name">Nama kk:</label>
            <input type="text" class="form-control" id="kk_name" name="kk_name">
        </div>
        <div class="form-group">
            <label for="complaint">Keluhan:</label>
            <input type="text" class="form-control" id="complaint" name="complaint" required>
        </div>
        <div class="form-group">
            <label for="poli">Poli:</label>
            <select class="form-control" id="poli" name="poli" required>
                <option value="Umum">Umum</option>
                <option value="Gigi">Gigi</option>
                <option value="KIA">KIA</option>
                <option value="KB">KB</option>
                <option value="TB">TB</option>
                <option value="VK">VK</option>
                <option value="IGD">IGD</option>
                <option value="Inap">Inap</option>
            </select>
        </div>
        <div class="form-group">
            <label for="patient_type">Jenis Pasien:</label>
            <select class="form-control" id="patient_type" name="patient_type" required>
                <option value="Umum">Umum</option>
                <option value="BPJS">BPJS</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="reset" class="btn btn-danger">Batal</button>
    </form>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('search-nik').addEventListener('click', function() {
        let nik = document.getElementById('nik').value;
        fetch(`/check-old-patient?nik=${nik}`)
            .then(response => response.json())
            .then(data => {
                if(data.exists) {
                    // Membuat URL untuk pendaftaran pasien lama dengan data yang dikirimkan
                    let url = `/oldpatients/create?nik=${data.nik}&norm=${data.norm}&name=${data.name}&date_of_birth=${data.date_of_birth}&gender=${data.gender}&address=${data.address}&occupation=${data.occupation}&kk_name=${data.kk_name}`;
                    window.location.href = url;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Pasien belum terdaftar, silahkan isi pendaftaran'
                    });
                }
            });
    });

    document.getElementById('search-norm').addEventListener('click', function() {
        let norm = document.getElementById('norm').value;
        fetch(`/check-old-patient?norm=${norm}`)
            .then(response => response.json())
            .then(data => {
                if(data.exists) {
                    // Membuat URL untuk pendaftaran pasien lama dengan data yang dikirimkan
                    let url = `/oldpatients/create?nik=${data.nik}&norm=${data.norm}&name=${data.name}&date_of_birth=${data.date_of_birth}&gender=${data.gender}&address=${data.address}&occupation=${data.occupation}&kk_name=${data.kk_name}`;
                    window.location.href = url;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Pasien belum terdaftar, silahkan isi pendaftaran'
                    });
                }
            });
    });
</script>

@stop
