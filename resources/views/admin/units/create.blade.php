@extends('layouts.app')

@section('dashboard')
	Units
	<small>Tambah Unit</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/units')}}">Unit</a></li>
	<li class="active">Tambah Unit</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Unit</h3>
					{{ Form::open(['url'=> route('units.store'), 'method'=>'post' ]) }}
						@include('admin.units._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection