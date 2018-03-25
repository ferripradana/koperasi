@extends('layouts.app')

@section('dashboard')
   Ubah Angsuran
   <small>Ubah Transaksi Angsuran</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Peminjaman</a></li>
   <li><a href="{{ url('/admin/loan/angsuran') }}">Angsuran</a></li>
   <li class="active">Ubah Transaksi Angsuran</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Angsuran</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($angsuran, ['url' => route('angsuran.update', $angsuran->id), 'method' => 'put']) !!}
                    @include('admin.angsuran._form')
                    <input type="hidden" name="edited_by" value="{{ auth()->user()->id }}">
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection