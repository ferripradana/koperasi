@extends('layouts.app')

@section('dashboard')
	Penarikan
	<small>Transaksi Penarikan</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Income</a></li>
	<li><a href="{{url('/admin/transaction/penarikan')}}">Penarikan</a></li>
	<li class="active">Create Penarikan</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Create Penarikan</h3>
					{{ Form::open(['url'=> route('penarikan.store'), 'method'=>'post' ]) }}
						@include('admin.penarikan._form')
						<input type="hidden" name="created_by" value="{{ auth()->user()->id }}">
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection