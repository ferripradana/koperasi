@extends('layouts.app')

@section('dashboard')
   Unit
   <small>Ubah Unit</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/units') }}">Unit</a></li>
   <li class="active">Ubah Unit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Unit</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($unit, ['url' => route('units.update', $unit->id), 'method' => 'put']) !!}
                    @include('admin.units._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection