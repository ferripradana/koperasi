<?php 
	$_notransaksi   = "ANGS".date("dmY").sprintf("%07d", \App\Model\Angsuran::count('id') + 1 );
	$no_transaksi   = isset( $angsuran->no_transaksi ) ?  $angsuran->no_transaksi :$_notransaksi; 
	$anggota_option = ['' => '-- Pilih Anggota --'] + App\Model\Anggota::select(
			          DB::raw("CONCAT(nik,'-',nama) AS name"),'id')
			              ->pluck('name', 'id')->toArray();
?>
<div class="box-body">
	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_anggota') ? ' has-error' : '' }}">
	 	{{ Form::label('id_anggota', 'Nama Anggota') }}
	 	{!! Form::select('id_anggota', $anggota_option, null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_anggota']) !!}
	 	{!! $errors->first('id_anggota','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_pinjaman') ? ' has-error' : '' }}">
	 	{{ Form::label('id_pinjaman', 'No. Pinjaman') }}
	 	{!! Form::select('id_pinjaman', [], null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_pinjaman']) !!}
	 	{!! $errors->first('id_pinjaman','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-12" id='loader' style='display: none;'>
		<div>
		    <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='50px' height='50px'></center>
		</div>
	</div>
	
	 <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal_transaksi', 'Tanggal Transaksi') }}
	 	{{ Form::text('tanggal_transaksi', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Transaksi', 'required'=>'required', 'readonly'=>'readonly', 'id'=> 'tanggal_transaksi' ]) }}
	 	{!! $errors->first('tanggal_transaksi','<p class="help-block">:message</p>') !!}
	 </div>

	<div class="form-group col-md-6 has-feedback{{$errors->has('no_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('no_transaksi', 'No. Transaksi') }}
	 	{{ Form::text('no_transaksi', $no_transaksi, ['class'=>'form-control', 'placeholder'=> 'No. Transaksi', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
	 	{!! $errors->first('no_transaksi','<p class="help-block">:message</p>') !!}
	 </div>

	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_proyeksi') ? ' has-error' : '' }}">
	 	{{ Form::label('id_proyeksi', 'Proyeksi') }}
	 	{!! Form::select('id_proyeksi', [], null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_proyeksi']) !!}
	 	{!! $errors->first('id_proyeksi','<p class="help-block">:message</p>') !!}
	 </div>
	 

	<div class="form-group col-md-6 has-feedback{{$errors->has('angsuran_ke') ? ' has-error' : '' }}">
	 	{{ Form::label('angsuran_ke', 'Angsuran Ke') }}
	 	{{ Form::text('angsuran_ke', null, ['class'=>'form-control', 'placeholder'=> 'Angsuran Ke', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
	 	{!! $errors->first('angsuran_ke','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-6 has-feedback{{$errors->has('pokok') ? ' has-error' : '' }}">
	 	{{ Form::label('pokok', 'Pokok Bulanan') }}
	 	{{ Form::text('pokok', null, ['class'=>'form-control', 'placeholder'=> 'Pokok Bulanan', 'required'=>'required', 'readonly'=> 'readonly' , 'id' => 'pokok' ]) }}
	 	{!! $errors->first('pokok','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-6 has-feedback{{$errors->has('bunga') ? ' has-error' : '' }}">
	 	{{ Form::label('bunga', 'Bunga Bulanan') }}
	 	{{ Form::text('bunga', null, ['class'=>'form-control', 'placeholder'=> 'Bunga Bulanan', 'required'=>'required', 'readonly'=> 'readonly' , 'id' => 'bunga' ]) }}
	 	{!! $errors->first('bunga','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-6 has-feedback{{$errors->has('simpanan_wajib') ? ' has-error' : '' }}">
	 	{{ Form::label('simpanan_wajib', 'Simpanan Wajib') }}
	 	{{ Form::text('simpanan_wajib', null, ['class'=>'form-control', 'placeholder'=> 'Simpanan Wajib', 'required'=>'required', 'readonly'=> 'readonly' , 'id' => 'simpanan_wajib' ]) }}
	 	{!! $errors->first('simpanan_wajib','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-6 has-feedback{{$errors->has('denda') ? ' has-error' : '' }}">
	 	{{ Form::label('denda', 'Denda') }}
	 	{{ Form::text('denda', null, ['class'=>'form-control', 'placeholder'=> 'Denda', 'required'=>'required' , 'id' => 'denda', 'readonly' => 'readonly' ]) }}
	 	{!! $errors->first('denda','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-12 has-feedback{{$errors->has('total') ? ' has-error' : '' }}">
	 	{{ Form::label('total', 'Total') }}
	 	{{ Form::text('total', null, ['class'=>'form-control', 'placeholder'=> 'Total', 'required'=>'required' , 'id' => 'total', 'readonly' => 'readonly' ]) }}
	 	{!! $errors->first('total','<p class="help-block">:message</p>') !!}
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
	$("#angsuran_ke").number(true, 0);
	$('#pokok').number( true, 0 );
	$('#bunga').number( true, 0 );
	$("#simpanan_wajib").number(true, 0);
	$("#denda").number(true, 0);
	$("#total").number(true, 0);


	var getpinjamanurl = "{{ route('angsuran.viewpeminjaman') }}";
	$("#id_anggota").change(function(){
			$("#id_proyeksi").html('');
			$.ajax({
		                url: getpinjamanurl,
		                type: 'GET',
		                dataType: 'JSON',
		                data: 'id_anggota=' + this.value ,
		                beforeSend: function() {
		                    $("#loader").show();		         
		                },
		                success: function(data) {
		                	var html = '<option>-- Pilih Pinjaman --</option>';
                     		for (var i = 0; i < data.length; i++) {
                     			html += '<option value="'+data[i].id+'">'+data[i].no_transaksi+'</option>'
                     		}
                     		$("#id_pinjaman").html(html);
		                   $("#loader").hide();  
		                }
		    });
	});
	var getproyeksiurl = "{{ route('angsuran.viewproyeksi') }}";
	$("#id_pinjaman").change(function(){
			$.ajax({
		                url: getproyeksiurl,
		                type: 'GET',
		                dataType: 'JSON',
		                data: 'id_pinjaman=' + this.value ,
		                beforeSend: function() {
		                    $("#loader").show();		         
		                },
		                success: function(data) {
		                	var html = '<option>-- Pilih Angsuran --</option>';
                     		for (var i = 0; i < data.length; i++) {
                     			html += '<option value="'+data[i].id+'">('+data[i].angsuran_ke+") "+data[i].tgl_proyeksi+'</option>'
                     		}
                     		$("#id_proyeksi").html(html);
		                   $("#loader").hide();  
		                }
		    });
	});


	var getdetailproyeksi = "{{ route('angsuran.viewdetailproyeksi') }}";
	$("#id_proyeksi").change(function(){
			$.ajax({
		                url: getdetailproyeksi,
		                type: 'GET',
		                dataType: 'JSON',
		                data: 'id_proyeksi=' + this.value ,
		                beforeSend: function() {
		                    $("#loader").show();		         
		                },
		                success: function(data) {
		                  $("#angsuran_ke").val(data.angsuran_ke);
		                  $("#pokok").val(data.cicilan);
		                  $("#bunga").val(data.bunga_nominal);
		                  $("#simpanan_wajib").val(data.simpanan_wajib);
		                  
		                  $("#denda").val(data.denda);  

		                  var total = parseFloat($('#pokok').val()) +parseFloat($('#bunga').val())
		                  			  + parseFloat($('#simpanan_wajib').val()) + parseFloat($('#denda').val())  ;

		                   $("#total").val(total);
		                   $("#loader").hide();
		                }
		    });
	});

	$('form').on('submit', function(e) {
	    $('#angsuran_ke').number(true, 2, '.', '');
		$('#pokok').number(true, 2, '.', '');
		$('#bunga').number(true, 2, '.', '');
		$('#simpanan_wajib').number(true, 2, '.', '');
		$('#denda').number(true, 2, '.', '');
		$('#total').number(true, 2, '.', '');
	});


	$(document).ready(function(){
		@if(isset($angsuran->id_anggota))
				$.ajax({
		                url: getpinjamanurl,
		                type: 'GET',
		                dataType: 'JSON',
		                data: 'id_anggota=' + '{{$angsuran->id_anggota}}' ,
		                beforeSend: function() {
		                    $("#loader").show();		         
		                },
		                success: function(data) {
		                	var html = '<option>-- Pilih Pinjaman --</option>';
                     		for (var i = 0; i < data.length; i++) {
                     			html += '<option value="'+data[i].id+'">'+data[i].no_transaksi+'</option>'
                     		}
                     		$("#id_pinjaman").html(html);
                     		$("#id_pinjaman").val('{{$angsuran->id_pinjaman}}');
		                   $("#loader").hide();  
		                }
		    	});
		@endif
		@if(isset($angsuran->id_pinjaman))
				$.ajax({
		                url: getproyeksiurl,
		                type: 'GET',
		                dataType: 'JSON',
		                data: 'id_pinjaman=' + '{{$angsuran->id_pinjaman}}' ,
		                beforeSend: function() {
		                    $("#loader").show();		         
		                },
		                success: function(data) {
		                	var html = '<option>-- Pilih Angsuran --</option>';
                     		for (var i = 0; i < data.length; i++) {
                     			html += '<option value="'+data[i].id+'">('+data[i].angsuran_ke+") "+data[i].tgl_proyeksi+'</option>'
                     		}
                     		$("#id_proyeksi").html(html);
                     		$("#id_proyeksi").val("{{$angsuran->id_proyeksi}}");
		                   $("#loader").hide();  
		                }
		    });
		@endif
	});







	
</script>
