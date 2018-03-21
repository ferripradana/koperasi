@extends('layouts.app')

@section('dashboard')
   COA
   <small>Ubah COA</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/coa') }}">COA</a></li>
   <li class="active">Ubah COA</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah COA</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($coa, ['url' => route('coa.update', $coa->id), 'method' => 'put']) !!}
                    @include('admin.coa._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection