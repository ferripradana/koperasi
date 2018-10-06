@extends('layouts.app')

@section('dashboard')
   Ubah Transaksi Unit
   <small>Ubah Transaksi Unit</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/transaction/transaksiunit') }}">Transaksi Unit</a></li>
   <li class="active">Ubah Transaksi Unit </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Transaksi Unit</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($transaksiunit, ['url' => route('transaksiunit.update', $transaksiunit->id), 'method' => 'put']) !!}
                    @include('admin.transaksiunit._form')
                    <input type="hidden" name="edited_by" value="{{ auth()->user()->id }}">
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection