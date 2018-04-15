<?php 
	$_notransaksi = \App\Helpers\Common::getNoTransaksi('simpanan');
	$no_transaksi = isset( $simpanan->no_transaksi ) ?  $simpanan->no_transaksi :$_notransaksi; 
?>
<div class="box-body">
	 <div class="form-group col-md-6 has-feedback{{$errors->has('nama') ? ' has-error' : '' }}">
	 	{{ Form::label('no_transaksi', 'No. Transaksi') }}
	 	{{ Form::text('no_transaksi', $no_transaksi, ['class'=>'form-control', 'placeholder'=> 'Name', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
	 	{!! $errors->first('no_transaksi','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal_transaksi', 'Tanggal Transaksi') }}
	 	{{ Form::text('tanggal_transaksi', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Transaksi', 'required'=>'required', 'readonly'=>'readonly']) }}
	 	{!! $errors->first('tanggal_transaksi','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group col-md-6 has-feedback{{$errors->has('id_anggota') ? ' has-error' : '' }}">
	 	{{ Form::label('id_anggota', 'Nama Anggota') }}
	 	{!! Form::select('id_anggota', $anggota = [''=>'-- Pilih Anggota --'] + App\Model\Anggota::pluck('nama','id')->all(), null, ['class' => 'form-control js-select2', 'required'=>'required']) !!}
	 	{!! $errors->first('id_anggota','<p class="help-block">:message</p>') !!}
	 </div>

	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_simpanan') ? ' has-error' : '' }}">
	 	{{ Form::label('id_simpanan', 'Nama Simpanan') }}
	 	{!! Form::select('id_simpanan', $jenissimpanan = [''=>'-- Pilih Simpanan --']	+ App\Model\JenisSimpanan::pluck('nama_simpanan','id')->all(), null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('id_simpanan','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-12 has-feedback{{$errors->has('nominal') ? ' has-error' : '' }}">
	 	{{ Form::label('nominal', 'Nominal') }}
	 	{{ Form::text('nominal', null, ['class'=>'form-control ', 'placeholder'=> 'Nominal', 'required'=>'required', 'id'=>'nominal']) }}
	 	{!! $errors->first('nominal','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-12 has-feedback{{$errors->has('keterangan') ? ' has-error' : '' }}">
	 	{{ Form::label('keterangan', 'Keterangan') }}
	 	{{ Form::textarea('keterangan', null, ['class'=>'form-control ', 'placeholder'=> 'Description']) }}
	 	{!! $errors->first('keterangan','<p class="help-block">:message</p>') !!}
	 </div>

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>

<script src="{{ asset('/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('/js/jquerynumber/jquery.number.js') }}"></script>
<script type="text/javascript">
    $('.date').datepicker({  
       format: 'dd-mm-yyyy',
       todayHighlight: true
     });  

    $('#nominal').number( true, 0 );

    $('form').on('submit', function(e) {
	    $('#nominal').number(true, 2, '.', '');
	 });
</script>  