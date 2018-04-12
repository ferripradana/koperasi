@extends('layouts.app')

@section('dashboard')
   Transaksi Lain - Lain
   <small>Daftar Transaksi Lain - Lain</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Transaksi Lain - Lain</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Transaksi Lain - Lain</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p>
                        <a class="btn btn-success" href="{{ route('lain.create') }}"><i class="fa fa-plus"></i> Tambah</a> 
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