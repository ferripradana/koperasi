@extends('layouts.app')

@section('dashboard')
    403 Akses dilarang
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">403 Akses Dilarang</li>
@endsection

@section('content')
    <div class="error-page">
        <h2 class="headline text-danger"> 403</h2>

        <div class="error-content">
            <h3><i class="fa fa-warning text-danger"></i> Oops! Akses dilarang.</h3>

            <p>
                Anda mengakses halaman yang dilarang.
                Mungkin anda bisa <a href="{{ url('home') }}">kembali ke dashboard</a>.
            </p>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
@endsection
