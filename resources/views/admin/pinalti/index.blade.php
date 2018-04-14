@extends('layouts.app')

@section('dashboard')
   Pinalti
   <small>Pinalti</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="#"><i class="fa fa-book"></i> Peminjaman</a></li>
   <li class="active">Pinalti</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Pinalti</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p>
                        <a class="btn btn-success" href="{{ route('pinalti.create') }}"><i class="fa fa-plus"></i>&nbsp;Create</a> 
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