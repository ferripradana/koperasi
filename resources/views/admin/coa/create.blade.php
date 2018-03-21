@extends('layouts.app')

@section('dashboard')
	COA
	<small>Tambah COA</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/coa')}}">COA</a></li>
	<li class="active">Tambah COA</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah COA</h3>
					{{ Form::open(['url'=> route('coa.store'), 'method'=>'post' ]) }}
						@include('admin.coa._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection