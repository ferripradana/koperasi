@extends('layouts.app')

@section('dashboard')
    <h1>404 Halaman Tidak Ditemukan</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">404 Halaman Tidak Ditemukan</li>
@endsection

@section('content')
    <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Oops! Halaman Tidak Ditemukan.</h3>

            <p>
                Halaman yang anda cari tidak ditemukan.
                Mungkin anda bisa <a href="{{ url('home') }}">kembali ke dashboard</a>.
            </p>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
@endsection
