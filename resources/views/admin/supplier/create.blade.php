@extends('layouts.app')

@section('dashboard')
	Supplier
	<small>Tambah Supplier</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/supplier')}}">Supplier</a></li>
	<li class="active">Tambah Supplier</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Supplier</h3>
					{{ Form::open(['url'=> route('supplier.store'), 'method'=>'post', 'files'=>'true' ]) }}
						@include('admin.supplier._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection