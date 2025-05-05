@extends('layout.app')
@section('title','Update Data')

@section('nav')
@include('layout.nav')


@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card p-4">
            <h1>Login</h1>
 <form action="{{ route('user.update-data.post') }}" method="post">
                @csrf
                 @include('layout.notif')
                <div class="mb-2">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" disabled class="form-control" id="email" name="email" " required value="{{ Auth::user()->email }}">
                </div>
                <div class="mb-2">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name')? old('name'): Auth::user()->name }}" >
                </div>
                <h3>Password</h3>
                <div class="form-text">Silahkan masukan password jika akan melakukan pergantian password</div>
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" >
                </div>
                <div class="mb-2">
                    <label for="password-confirmation" class="form-label">Konfifrmasi Password</label>
                    <input type="password" class="form-control" name="password-confirmation" id="password-confirmation" >
                </div>
                <div class="d-inline">
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection