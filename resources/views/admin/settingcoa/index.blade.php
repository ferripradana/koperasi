@extends('layouts.app')

@section('dashboard')
	Setting COA
	<small>Setting COA</small>
@endsection

@section('breadcrumb')
	<li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
	<li><a href="#"><i class="fa fa-cog"></i> Pengaturan</a></li>
	<li class="active">Setting COA</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Setting COA</h3>
					{{ Form::open(['url'=> route('settingcoa.store'), 'method'=>'post', 'files'=>'true' ]) }}
						@include('admin.settingcoa._form')
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection