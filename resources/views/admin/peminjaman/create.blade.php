@extends('layouts.app')

@section('dashboard')
	Peminjaman
	<small>Transaksi Peminjaman</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-book"></i> Peminjaman</a></li>
	<li><a href="{{url('/admin/loan/peminjaman')}}">Peminjaman</a></li>
	<li class="active">Create Peminjaman</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Create Peminjaman</h3>
					{{ Form::open(['url'=> route('peminjaman.store'), 'method'=>'post' ]) }}
						@include('admin.peminjaman._form')
						<input type="hidden" name="created_by" value="{{ auth()->user()->id }}">
						<input type="hidden" name="status" value="0">
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	    $('.date').datepicker({  
	       format: 'dd-mm-yyyy',
	       todayHighlight: true
	     });  
	    var today = '{{ date("d-m-Y") }}';
    	$('#tanggal_pengajuan').val(today);
    	$('#bunga_persen').val(1);
	</script>  
@endsection
