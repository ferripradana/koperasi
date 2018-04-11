@extends('layouts.app')

@section('dashboard')
	Jenis Transaksi
	<small>Tambah Jenis Transaksi</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/jenistransaksi')}}">Jenis Transaksi</a></li>
	<li class="active">Tambah Jenis Transaksi</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Jenis Transaksi</h3>
					{{ Form::open(['url'=> route('jenistransaksi.store'), 'method'=>'post' ]) }}
						@include('admin.jenistransaksi._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection