{{-- resources/views/patients/create.blade.php --}}

@extends('layout.app')

@section('title', 'Register Patient')

@section('content_header')
<center>
    <h1>Pendaftaran Pasien Lama</h1>
</center>
@stop

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('oldpatients.store') }}" method="POST">
        @csrf
        <div class="form-group row">
            <label for="nik" class="col-sm-2 col-form-label">NIK:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="nik" name="nik" required>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" id="search-nik-old">Cari</button>
            </div>
        </div>
        <div class="form-group row">
            <label for="nik" class="col-sm-2 col-form-label">No Reka Medis:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="norm" name="norm" required>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" id="search-norm-old">Cari</button>
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
        <button type="submit" class="btn btn-primary" onclick="clearUrl()">Simpan</button>
        <button type="reset" class="btn btn-danger">Batal</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('search-nik-old').addEventListener('click', function () {
        let nik = document.getElementById('nik').value;
        fetch(`/check-old-patient?nik=${nik}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('nik').value = data.nik;
                    document.getElementById('norm').value = data.norm;
                    document.getElementById('name').value = data.name;
                    document.getElementById('date_of_birth').value = data.date_of_birth;
                    document.getElementById('gender').value = data.gender;
                    document.getElementById('address').value = data.address;
                    document.getElementById('occupation').value = data.occupation;
                    document.getElementById('kk_name').value = data.kk_name;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Data pasien lama tidak ditemukan, silahkan isi pendaftaran pasien baru'
                    });
                }
            });
    });

    document.getElementById('search-norm-old').addEventListener('click', function () {
        let norm = document.getElementById('norm').value;
        fetch(`/check-old-patient?norm=${norm}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('nik').value = data.nik;
                    document.getElementById('name').value = data.name;
                    document.getElementById('date_of_birth').value = data.date_of_birth;
                    document.getElementById('gender').value = data.gender;
                    document.getElementById('address').value = data.address;
                    document.getElementById('occupation').value = data.occupation;
                    document.getElementById('kk_name').value = data.kk_name;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Data pasien lama tidak ditemukan, silahkan isi pendaftaran pasien baru'
                    });
                }
            });
    });
</script>

<script>
    // Mendapatkan parameter dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const nik = urlParams.get('nik');
    const norm = urlParams.get('norm');
    const name = urlParams.get('name');
    const dateOfBirth = urlParams.get('date_of_birth');
    const gender = urlParams.get('gender');
    const address = urlParams.get('address');
    const occupation = urlParams.get('occupation');
    const kkName = urlParams.get('kk_name');

    // Mengisi nilai field-field dengan nilai dari parameter URL
    document.getElementById('nik').value = nik;
    document.getElementById('norm').value = norm;
    document.getElementById('name').value = name;
    document.getElementById('date_of_birth').value = dateOfBirth;
    document.getElementById('gender').value = gender;
    document.getElementById('address').value = address;
    document.getElementById('occupation').value = occupation;
    document.getElementById('kk_name').value = kkName;

       // Mendapatkan URL tanpa parameter
const baseUrl = window.location.origin + window.location.pathname;

// Fungsi untuk menghapus data dari URL
function clearUrl() {
    history.replaceState(null, null, baseUrl);
}

// Memanggil fungsi clearUrl() saat tombol "Simpan" atau "Batal" ditekan
document.querySelector('button[type="submit"]').addEventListener('click', clearUrl);
document.querySelector('button[type="reset"]').addEventListener('click', clearUrl);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var alertMessage = '{{ session('alert') }}';
        if (alertMessage) {
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: alertMessage
            });
        }
    });
</script>
@stop