@extends('layouts.app')

@section('dashboard')
   Group COA
   <small>Ubah Group COA</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
   <li><a href="{{ url('/admin/master/coagroups') }}">Group COA</a></li>
   <li class="active">Ubah Group COA</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Unit</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($group, ['url' => route('coagroups.update', $group->id), 'method' => 'put']) !!}
                    @include('admin.coagroup._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection