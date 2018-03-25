@extends('layouts.app')

@section('dashboard')
	Angsuran
	<small>Angsuran</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Peminjaman</a></li>
	<li><a href="{{url('/admin/loan/angsuran')}}">Angsuran</a></li>
	<li class="active">Create Angsuran</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Create Angsuran</h3>
					{{ Form::open(['url'=> route('angsuran.store'), 'method'=>'post' ]) }}
						@include('admin.angsuran._form')
						<input type="hidden" name="created_by" value="{{ auth()->user()->id }}">
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	    var today = '{{ date("d-m-Y") }}';
    	$('#tanggal_transaksi').val(today);
	</script> 
@endsection
