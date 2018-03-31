@extends('layouts.app')

@section('dashboard')
	Dashboard
	<small>Admin</small>
@endsection
@section('breadcrumb')
	<li>
		<a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a>
	</li>
	<li class="active">Dashboard</li>
@endsection


@section('content')
	<!-- welcome -->
	<div class="row">
		<div class="col-md-12">
			<div class="callout callout-success">
				<h4>Selamat Datang Di Koperasi Amigo</h4>
				<p>Sistem Koperasi Amigo Group</p>
			</div>
		</div>
	</div>

	<!-- Info Boxes -->
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua">
					<i class="ion ion-ios-person-outline"></i>
				</span>
				<div class="info-box-content">
					<span class="info-box-text">
						Total Anggota
					</span>
					<span class="info-box-number">
						<?php 
							 $jumlah =	App\Model\Anggota::count();
						 	echo $jumlah;
						 ?>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua">
					<i class="ion ion-cash"></i>
				</span>
				<div class="info-box-content">
					<span class="info-box-text">
						Total Pinjaman
					</span>
					<span class="info-box-number">
						<?php 
							 $jumlah =	App\Model\Peminjaman::where('status',1)->sum('nominal');
						 	echo  number_format($jumlah,2,",",".");
						 ?>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua">
					<i class="ion ion-social-usd"></i>
				</span>
				<div class="info-box-content">
					<span class="info-box-text">
						Total Saldo Simpanan
					</span>
					<span class="info-box-number">
						<?php 
							 $jumlah =	App\Model\Simpanan::sum('nominal') - App\Model\Penarikan::sum('nominal');
						 	echo  number_format($jumlah,2,",",".");
						 ?>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua">
					<i class="ion ion-social-usd-outline"></i>
				</span>
				<div class="info-box-content">
					<span class="info-box-text">
						Total Saldo Pinjaman
					</span>
					<span class="info-box-number">
						<?php 
							$jumlah_pinjaman =	App\Model\Peminjaman::where('status',1)->sum('nominal');
						 	$angsuran = App\Model\Angsuran::where('status',1)->sum('pokok');
						 	echo  number_format($jumlah_pinjaman - $angsuran,2,",",".");
						 ?>
					</span>
				</div>
			</div>
		</div>
	</div>
@endsection