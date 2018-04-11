@extends('layouts.app')

@section('dashboard')
   Jenis Transaksi
   <small>Ubah Jenis Transaksi</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/jenistransaksi') }}">Jenis Transaksi</a></li>
   <li class="active">Ubah Jenis Transaksi</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Transaksi</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($jenistransaksi, ['url' => route('jenistransaksi.update', $jenistransaksi->id), 'method' => 'put']) !!}
                    @include('admin.jenistransaksi._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection