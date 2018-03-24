@extends('layouts.app')

@section('dashboard')
   Peminjaman
   <small>Daftar Transaksi Peminjaman</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="#"><i class="fa fa-book"></i> Income</a></li>
   <li class="active">Peminjaman</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Peminjaman</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p>
                        <a class="btn btn-success" href="{{ route('peminjaman.create') }}"><i class="fa fa-plus"></i> Tambah</a> 
                    </p>
                    {!! $html->table(['class' => 'table table-bordered table-striped']) !!}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection