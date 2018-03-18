@extends('layouts.app')

@section('dashboard')
	Users
	<small>Tambah User</small>
@endsection	


@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	 <li><a href="#"><i class="fa fa-cog"></i> Pengaturan</a></li>
	<li><a href="{{url('/admin/users')}}">Users</a></li>
	 <li class="active">Tambah Users</li>
@endsection


@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Users</h3>
					{!! Form::open(['url'=>route('users.store'), 'method'=> 'post'])  !!}
						@include('admin.users._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection