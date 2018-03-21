@extends('layouts.app')

@section('dashboard')
   COA
   <small>COA</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li class="active">COA</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">COA</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p><a class="btn btn-success" href="{{ route('coa.create') }}">Tambah</a>  
                    </p>
                    {{ $helper->printTree($coa) }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection