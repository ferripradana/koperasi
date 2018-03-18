@extends('layouts.app')

@section('dashboard')
	Keterangan
	<small>Tambah Keterangan</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/keteranganpinjaman')}}">Keterangan Pinjaman</a></li>
	<li class="active">Tambah Keterangan Pinjaman</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Keterangan Pinjaman</h3>
					{{ Form::open(['url'=> route('keteranganpinjaman.store'), 'method'=>'post' ]) }}
						@include('admin.keterangan._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection