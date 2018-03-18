@extends('layouts.app')

@section('dashboard')
	Jenis Simpanan
	<small>Tambah Jenis Simpanan</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/jenissimpanan')}}">Jenis Simpanan</a></li>
	<li class="active">Tambah Jenis Simpanan</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Jenis Simpanan</h3>
					{{ Form::open(['url'=> route('jenissimpanan.store'), 'method'=>'post' ]) }}
						@include('admin.jenissimpanan._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection