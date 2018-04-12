@extends('layouts.app')

@section('dashboard')
   Ubah Transaksi Lain
   <small>Ubah Transaksi Lain</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/transaction/lain') }}">Transaksi Lain</a></li>
   <li class="active">Ubah Transaksi Lain </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Transaksi Lain</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($transaksi, ['url' => route('lain.update', $transaksi->id), 'method' => 'put']) !!}
                    @include('admin.transaksi._form')
                    <input type="hidden" name="edited_by" value="{{ auth()->user()->id }}">
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection