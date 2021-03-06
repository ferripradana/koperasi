@extends('layouts.app')

@section('dashboard')
   Jabatan
   <small>Ubah Jabatan</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/jabatan') }}">Jabatan</a></li>
   <li class="active">Ubah Jabatan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Jabatan</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($jabatan, ['url' => route('jabatan.update', $jabatan->id), 'method' => 'put']) !!}
                    @include('admin.jabatan._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection