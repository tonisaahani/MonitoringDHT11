@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Buat Password</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required
                    autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Password</button>
        </form>
    </div>
@endsection
