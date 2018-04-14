@extends('layouts.app')

@section('dashboard')
   Ubah Pinalti
   <small>Ubah Transaksi Pinalti</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Peminjaman</a></li>
   <li><a href="{{ url('/admin/loan/pinalti') }}">Pinalti</a></li>
   <li class="active">Ubah Transaksi Pinalti</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Pinalti</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($pinalti, ['url' => route('pinalti.update', $pinalti->id), 'method' => 'put']) !!}
                    @include('admin.pinalti._form')
                    <input type="hidden" name="edited_by" value="{{ auth()->user()->id }}">
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection