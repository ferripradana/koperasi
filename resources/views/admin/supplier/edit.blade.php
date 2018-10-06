@extends('layouts.app')

@section('dashboard')
   Supplier
   <small>Ubah Supplier</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/supplier') }}">Supplier</a></li>
   <li class="active">Ubah Supplier</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Supplier</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($supplier, ['url' => route('supplier.update', $supplier->id), 'method' => 'put', 'files'=> 'true']) !!}
                    @include('admin.supplier._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection