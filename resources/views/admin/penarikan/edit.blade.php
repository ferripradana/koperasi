@extends('layouts.app')

@section('dashboard')
   Ubah Transaksi Penarikan
   <small>Ubah Transaksi Penarikan</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Income</a></li>
   <li><a href="{{ url('/admin/transaction/penarikan') }}">Penarikan</a></li>
   <li class="active">Ubah Transaksi Penarikan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Penarikan</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($penarikan, ['url' => route('penarikan.update', $penarikan->id), 'method' => 'put']) !!}
                    @include('admin.penarikan._form')
                    <input type="hidden" name="edited_by" value="{{ auth()->user()->id }}">
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection