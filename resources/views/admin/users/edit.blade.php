@extends('layouts.app')

@section('dashboard')
   Users
   <small>Ubah Users</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-cog"></i> Pengaturan</a></li>
   <li><a href="{{ url('/admin/users') }}">Users</a></li>
   <li class="active">Ubah User</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah User</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($user, ['url' => route('users.update', $user->id), 'method' => 'put']) !!}
                    @include('admin.users._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection