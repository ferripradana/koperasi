@extends('layouts.app')

@section('dashboard')
	Pinalti
	<small>Pinalti</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Peminjaman</a></li>
	<li><a href="{{url('/admin/loan/pinalti')}}">Pinalti</a></li>
	<li class="active">Create Pinalti</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Create Pinalti</h3>
					{{ Form::open(['url'=> route('pinalti.store'), 'method'=>'post' ]) }}
						@include('admin.pinalti._form')
						<input type="hidden" name="created_by" value="{{ auth()->user()->id }}">
						<input type="hidden" name="status" value="0">
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	    var today = '{{ date("d-m-Y") }}';
    	$('#tanggal').val(today);
	</script> 
@endsection
