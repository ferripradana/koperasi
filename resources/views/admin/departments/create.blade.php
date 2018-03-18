@extends('layouts.app')

@section('dashboard')
	Dartments
	<small>Tambah Department</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/departments')}}">Department</a></li>
	<li class="active">Tambah Department</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Department</h3>
					{{ Form::open(['url'=> route('departments.store'), 'method'=>'post' ]) }}
						@include('admin.departments._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection