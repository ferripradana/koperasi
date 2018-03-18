@extends('layouts.app')

@section('dashboard')
	Roles
	<small>Tambah Roles</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	 <li><a href="#"><i class="fa fa-cog"></i> Pengaturan</a></li>
	<li><a href="{{url('/admin/roles')}}">Roles</a></li>
	<li class="active">Tambah Roles</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Roles</h3>
					{{ Form::open(['url'=> route('roles.store'), 'method'=>'post' ]) }}
						@include('admin.roles._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection