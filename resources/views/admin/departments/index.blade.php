@extends('layouts.app')

@section('dashboard')
   Department
   <small>Daftar Department</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li class="active">Departments</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Departments</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p><a class="btn btn-success" href="{{ route('departments.create') }}">Tambah</a>
                    &nbsp;<a href="{{ url('admin/master/common/import/department') }}" class="btn btn-success"><span class="fa fa-download"></span>Import</a>
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