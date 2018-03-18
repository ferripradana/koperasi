@extends('layouts.app')

@section('dashboard')
   Department
   <small>Ubah Department</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/departments') }}">Departments</a></li>
   <li class="active">Ubah Department</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Department</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($department, ['url' => route('departments.update', $department->id), 'method' => 'put']) !!}
                    @include('admin.departments._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection