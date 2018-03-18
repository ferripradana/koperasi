@extends('layouts.app')

@section('dashboard')
   Anggota
   <small>Ubah Anggota</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/anggotas') }}">Anggota</a></li>
   <li class="active">Ubah Anggota</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Anggota</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($anggota, ['url' => route('anggotas.update', $anggota->id), 'method' => 'put', 'files'=> 'true']) !!}
                    @include('admin.anggotas._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection