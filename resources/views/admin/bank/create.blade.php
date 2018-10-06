@extends('layouts.app')

@section('dashboard')
	Bank
	<small>Master Bank</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Master</a></li>
	<li><a href="{{url('/admin/master/bank')}}">Bank</a></li>
	<li class="active">Tambah Bank</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Bank</h3>
					{{ Form::open(['url'=> route('bank.store'), 'method'=>'post' ]) }}
						@include('admin.bank._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection