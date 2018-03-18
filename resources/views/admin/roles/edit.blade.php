@extends('layouts.app')

@section('dashboard')
   Users
   <small>Ubah Roles</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-cog"></i> Pengaturan</a></li>
   <li><a href="{{ url('/admin/roles') }}">Roles</a></li>
   <li class="active">Ubah Roles</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Role</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($role, ['url' => route('roles.update', $role->id), 'method' => 'put']) !!}
                    @include('admin.roles._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection