@extends('layouts.app')

@section('dashboard')
	Transaksi Lain - Lain
	<small>Transaksi Lain - lain</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="{{url('/admin/transaction/lain')}}">Transaksi Lain - Lain</a></li>
	<li class="active">Create Transaksi Lain</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Create Transaksi Lain</h3>
					{{ Form::open(['url'=> route('lain.store'), 'method'=>'post' ]) }}
						@include('admin.transaksi._form')
						<input type="hidden" name="created_by" value="{{ auth()->user()->id }}">
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection