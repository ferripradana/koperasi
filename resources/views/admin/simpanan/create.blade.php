@extends('layouts.app')

@section('dashboard')
	Simpanan
	<small>Transaksi Simpanan</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Income</a></li>
	<li><a href="{{url('/admin/transaction/simpanan')}}">Simpanan</a></li>
	<li class="active">Create Simpanan</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Create Simpanan</h3>
					{{ Form::open(['url'=> route('simpanan.store'), 'method'=>'post' ]) }}
						@include('admin.simpanan._form')
						<input type="hidden" name="created_by" value="{{ auth()->user()->id }}">
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection