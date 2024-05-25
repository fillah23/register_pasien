@extends('layout.app')

@section('title', 'Add User')

@section('content_header')
<h1>Tambah User</h1>
@stop

@section('content')
<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Nama</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
        @error('name')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
        @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
@stop
