<?php 
	$_notransaksi = "PINJ".date("dmY").sprintf("%07d", \App\Model\Peminjaman::count('id') + 1 );
	$no_transaksi = isset( $peminjaman->no_transaksi ) ?  $peminjaman->no_transaksi :$_notransaksi; 
	$tenor_option = array();
	for ($i=5; $i <= 180 ; $i++) { 
		$tenor_option[$i] = $i;
	}
	$anggota_option = ['' => '-- Pilih Anggota --'] + App\Model\Anggota::select(
			          DB::raw("CONCAT(nik,'-',nama) AS name"),'id')
			              ->pluck('name', 'id')->toArray();
?>
<div class="box-body">
	 <div class="form-group col-md-6 has-feedback{{$errors->has('no_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('no_transaksi', 'No. Transaksi') }}
	 	{{ Form::text('no_transaksi', $no_transaksi, ['class'=>'form-control', 'placeholder'=> 'No. Transaksi', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
	 	{!! $errors->first('no_transaksi','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal_pengajuan') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal_pengajuan', 'Tanggal Pengajuan') }}
	 	{{ Form::text('tanggal_pengajuan', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Pengajuan', 'required'=>'required', 'readonly'=>'readonly', 'id'=> 'tanggal_pengajuan' ]) }}
	 	{!! $errors->first('tanggal_pengajuan','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group col-md-6 has-feedback{{$errors->has('id_anggota') ? ' has-error' : '' }}">
	 	{{ Form::label('id_anggota', 'Nama Anggota') }}
	 	{!! Form::select('id_anggota', $anggota_option , null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_anggota']) !!}
	 	{!! $errors->first('id_anggota','<p class="help-block">:message</p>') !!}
	 </div>

	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_keterangan_pinjaman') ? ' has-error' : '' }}">
	 	{{ Form::label('id_keterangan_pinjaman', 'Keterangan Pinjaman') }}
	 	{!! Form::select('id_keterangan_pinjaman', $keteranganpinjaman = [''=>'-- Guna Pinjaman --']	+ App\Model\KeteranganPinjaman::pluck('guna_pinjaman','id')->all(), null, ['class' => 'form-control js-select2' , 'id'=> 'id_keterangan_pinjaman']) !!}
	 	{!! $errors->first('id_keterangan_pinjaman','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-12 has-feedback{{$errors->has('nominal') ? ' has-error' : '' }}">
	 	{{ Form::label('nominal', 'Nominal') }}
	 	{{ Form::text('nominal', null, ['class'=>'form-control ', 'placeholder'=> 'Nominal', 'required'=>'required', 'id'=>'nominal']) }}
	 	{!! $errors->first('nominal','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('tenor') ? ' has-error' : '' }}">
	 	{{ Form::label('tenor', 'Tenor (Kali Angsuran)') }}
	 	{!! Form::select('tenor', $tenor_option , null, ['class' => 'form-control js-select2' , 'id'=> 'tenor']) !!}
	 	{!! $errors->first('tenor','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('bunga_persen') ? ' has-error' : '' }}">
	 	{{ Form::label('bunga_persen', 'Bunga (%)') }}
	 	{{ Form::text('bunga_persen', null, ['class'=>'form-control ', 'placeholder'=> 'Bunga (%)', 'required'=>'required']) }}
	 	{!! $errors->first('bunga_persen','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('cicilan') ? ' has-error' : '' }}">
	 	{{ Form::label('cicilan', 'Cicilan') }}
	 	{{ Form::text('cicilan', null, ['class'=>'form-control ', 'placeholder'=> 'Cicilan', 'required'=>'required', 'id'=> 'cicilan', 'readonly'=>'readonly']) }}
	 	{!! $errors->first('cicilan','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group col-md-6 has-feedback{{$errors->has('bunga_nominal') ? ' has-error' : '' }}">
	 	{{ Form::label('bunga_nominal', 'Bunga Nominal') }}
	 	{{ Form::text('bunga_nominal', null, ['class'=>'form-control ', 'placeholder'=> 'Bunga Nominal', 'required'=>'required', 'id'=> 'bunga_nominal', 'readonly'=> 'readonly']) }}
	 	{!! $errors->first('bunga_nominal','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('dana_resiko_credit') ? ' has-error' : '' }}">
	 	{{ Form::label('dana_resiko_credit', 'Dana Resiko Kredit') }}
	 	{{ Form::text('dana_resiko_credit', null, ['class'=>'form-control ', 'placeholder'=> 'Dana Resiko Kredit', 'required'=>'required', 'id'=> 'dana_resiko_credit']) }}
	 	{!! $errors->first('dana_resiko_credit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('nominal_diterima') ? ' has-error' : '' }}">
	 	{{ Form::label('nominal_diterima', 'Nominal Diterima') }}
	 	{{ Form::text('nominal_diterima', null, ['class'=>'form-control ', 'placeholder'=> 'Nominal Diterima', 'required'=>'required', 'id'=> 'nominal_diterima', 'readonly'=> 'readonly']) }}
	 	{!! $errors->first('nominal_diterima','<p class="help-block">:message</p>') !!}
	 </div>	


	 <div class="form-group col-md-12" id="detail">	 	
	 </div>
	 <div class="form-group col-md-12 has-feedback{{$errors->has('deskripsi') ? ' has-error' : '' }}">
	 	{{ Form::label('deskripsi', 'Keterangan') }}
	 	{{ Form::textarea('deskripsi', null, ['class'=>'form-control ', 'placeholder'=> 'Description']) }}
	 	{!! $errors->first('deskripsi','<p class="help-block">:message</p>') !!}
	 </div>

</div>


<div class="box-footer">
	@if(!isset($peminjaman) or (isset($peminjaman) &&  $peminjaman->status ==  0 ) )
    	{!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
    @endif
    @if(isset($peminjaman->id) && (auth()->user()->hasRole('superadmin')) && $peminjaman->status == 0 )
    	<input class="btn btn-primary" type="submit" name="approve" value="Approve">
    @endif
</div>

<script src="{{ asset('/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('/js/jquerynumber/jquery.number.js') }}"></script>
<script type="text/javascript">
	$('.date').datepicker({  
	       format: 'dd-mm-yyyy',
	       todayHighlight: true
	     });  
	@if(isset($peminjaman->id) &&  $peminjaman->status == 1 )
 		$('input[type=text]').attr('readonly', 'readonly');
 		$('#deskripsi').attr('readonly', 'readonly');
 		$('input[type=number]').attr('readonly', 'readonly');
 		$('.js-select2').attr('disabled', 'disabled');
 		$(document).ready(function(){
 			hitung();
 		})
	@endif

	$('#cicilan').number( true, 0 );
	$('#bunga_nominal').number( true, 0 );
	$('#nominal').number( true, 0 );
	$('#nominal_diterima').number( true, 0 );
	$('#bunga_persen').number( true, 2 );
	$('#dana_resiko_credit').number( true, 2 );

	$("#dana_resiko_credit").keyup(function(){
		hitung();
	})

	$( "#nominal" ).keyup(function() {
  		var nominal = parseFloat($('#nominal').val()) ;
  		if (nominal>5000000) {
  			$("#dana_resiko_credit").val(1);
	 	}else{
	 		$("#dana_resiko_credit").val(0);
	 	}
	 	hitung();
	});
	 $('#tenor').change(function() {
	 	hitung();
	 });
	 $( "#nominal" ).click(function() {
  		var nominal = parseFloat($('#nominal').val()) ;
  		if (nominal>5000000) {
  			$("#dana_resiko_credit").val(1);	
	 	}
	 	else{
	 		$("#dana_resiko_credit").val(0);
	 	}
	 	hitung();
	});
	 $('#tenor').click(function() {
	 	hitung();
	 });

	 $('#bunga_persen').keyup(function() {
	 	hitung();
	 });

	 $('#bunga_persen').click(function() {
	 	hitung();
	 });



	 function hitung(){
	 	var cicilan =  parseFloat($('#nominal').val()) / parseFloat($('#tenor').val());
	 	$("#cicilan").val(Math.round(cicilan) );
	 	var bunga_nominal =  parseFloat($('#nominal').val()) *  parseFloat($('#bunga_persen').val())/100;
	 	$("#bunga_nominal").val( Math.round(bunga_nominal) ); 
	 	var simpananwajib = 0;
	 	if (cicilan>0) {
	 		simpananwajib = 15000;
	 	}

	 	var nominal_diterima =  parseFloat($('#nominal').val()) - (parseFloat($("#dana_resiko_credit").val())*parseFloat($('#nominal').val()) / 100 );

	 	$("#nominal_diterima").val( Math.round(nominal_diterima||0) )

	 	var gt = bunga_nominal+cicilan+simpananwajib; 

	 	var detail = '<table class="table table-striped table-hover"><tr><td>Bulanan</td><td>:</td><td align="right">'+ cicilan.formatMoney(2, ',', '.') +'</td></tr>';
	 	detail += '<tr><td>Bunga Bulanan </td><td>:</td><td align="right">'+ bunga_nominal.formatMoney(2, ',', '.') +'</td></tr><tr><td>Simpanan Wajib</td><td>:</td><td align="right">'+simpananwajib.formatMoney(2, ',', '.')+'</td></tr><tr><td>Total Bulanan</td><td>:</td><td align="right">'+gt.formatMoney(2, ',', '.')+'</td></tr></table>';
	 	$('#detail').html(detail);
	 }

	 Number.prototype.formatMoney = function(c, d, t){
        var n = this, 
            c = isNaN(c = Math.abs(c)) ? 2 : c, 
            d = d == undefined ? "." : d, 
            t = t == undefined ? "," : t, 
            s = n < 0 ? "-" : "", 
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
            j = (j = i.length) > 3 ? j % 3 : 0;
           return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
         };

    $('form').on('submit', function(e) {
	    $('#cicilan').number(true, 2, '.', '');
		$('#bunga_nominal').number(true, 2, '.', '');
		$('#nominal').number(true, 2, '.', '');
		$('#nominal_diterima').number(true, 2, '.', '');
		$('#bunga_persen').number(true, 2, '.', '');
		$('#dana_resiko_credit').number(true, 2, '.', '');
	});

	 



</script>
