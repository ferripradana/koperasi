@extends('layouts.app')

@section('dashboard')
	Group COA
	<small>Tambah Group COA</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/coagroups')}}">Group COA</a></li>
	<li class="active">Tambah Group COA</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Jenis Simpanan</h3>
					{{ Form::open(['url'=> route('coagroups.store'), 'method'=>'post' ]) }}
						@include('admin.coagroup._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection