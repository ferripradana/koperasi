@extends('layouts.app')

@section('dashboard')
   Keterangan Pinjaman
   <small>Ubah Keterangan Pinjaman</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/keteranganpinjaman') }}">Keterangan Pinjaman</a></li>
   <li class="active">Ubah Keterangan Pinjaman</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Keterangan Pinjaman</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($keterangan, ['url' => route('keteranganpinjaman.update', $keterangan->id), 'method' => 'put']) !!}
                    @include('admin.keterangan._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection