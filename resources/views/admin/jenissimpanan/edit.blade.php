@extends('layouts.app')

@section('dashboard')
   Jenis Simpanan
   <small>Ubah Jenis Simpanan</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/jenissimpanan') }}">Jenis Simpanan</a></li>
   <li class="active">Ubah Jenis Simpanan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Jabatan</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($jenissimpanan, ['url' => route('jenissimpanan.update', $jenissimpanan->id), 'method' => 'put']) !!}
                    @include('admin.jenissimpanan._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection