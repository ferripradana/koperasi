<?php 
	$_notransaksi   = \App\Helpers\Common::getNoTransaksi('angsuran');
	$no_transaksi   = isset( $angsuran->no_transaksi ) ?  $angsuran->no_transaksi :$_notransaksi; 
	$anggota_option = ['' => '-- Pilih Anggota --'] + App\Model\Anggota::select(
			          DB::raw("CONCAT(nik,'-',nama) AS name"),'id')
			              ->pluck('name', 'id')->toArray();
    $id_pinjaman_option = [];
    if (isset($angsuran->id_pinjaman)) {
    	$id_pinjaman_option = App\Model\Peminjaman::where('id',$angsuran->id_pinjaman)->pluck('no_transaksi','id');
    }

    $id_proyeksi_option = [];
    if (isset($angsuran->id_proyeksi)) {
    	$id_proyeksi_option = App\Model\ProyeksiAngsuran::select(
			          DB::raw("CONCAT('(',angsuran_ke,')',' ', DATE_FORMAT(tanggal_proyeksi,'%d-%m-%Y' ) ) AS name"),'id')
    					  ->where('id',$angsuran->id_proyeksi)
			              ->pluck('name', 'id')->toArray();
    }

?>
<div class="box-body">
	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_anggota') ? ' has-error' : '' }}">
	 	{{ Form::label('id_anggota', 'Nama Anggota') }}
	 	{!! Form::select('id_anggota', $anggota_option, null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_anggota']) !!}
	 	{!! $errors->first('id_anggota','<p class="help-block">:message</p>') !!}
	 </div>
	 
	 <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal_transaksi', 'Tanggal Transaksi') }}
	 	{{ Form::text('tanggal_transaksi', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Transaksi', 'required'=>'required', 'readonly'=>'readonly', 'id'=> 'tanggal_transaksi' ]) }}
	 	{!! $errors->first('tanggal_transaksi','<p class="help-block">:message</p>') !!}
	 </div>

	<div class="form-group col-md-12" id='loader' style='display: none;'>
		<div>
		    <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='50px' height='50px'></center>
		</div>
	</div>
	
	<div class="form-group col-md-6 has-feedback{{$errors->has('id_pinjaman') ? ' has-error' : '' }}">
	 	{{ Form::label('id_pinjaman', 'No. Pinjaman') }}
	 	{!! Form::select('id_pinjaman', $id_pinjaman_option , null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_pinjaman']) !!}
	 	{!! $errors->first('id_pinjaman','<p class="help-block">:message</p>') !!}
	 </div>
	 

	<div class="form-group col-md-6 has-feedback{{$errors->has('no_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('no_transaksi', 'No. Transaksi') }}
	 	{{ Form::text('no_transaksi', $no_transaksi, ['class'=>'form-control', 'placeholder'=> 'No. Transaksi', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
	 	{!! $errors->first('no_transaksi','<p class="help-block">:message</p>') !!}
	 </div>

	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_proyeksi') ? ' has-error' : '' }}">
	 	{{ Form::label('id_proyeksi', 'Jatuh Tempo') }}
	 	{!! Form::select('id_proyeksi', $id_proyeksi_option, null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_proyeksi']) !!}
	 	{!! $errors->first('id_proyeksi','<p class="help-block">:message</p>') !!}
	 </div>
	 

	<div class="form-group col-md-6 has-feedback{{$errors->has('angsuran_ke') ? ' has-error' : '' }}">
	 	{{ Form::label('angsuran_ke', 'Angsuran Ke') }}
	 	{{ Form::text('angsuran_ke', null, ['class'=>'form-control', 'placeholder'=> 'Angsuran Ke', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
	 	{!! $errors->first('angsuran_ke','<p class="help-block">:message</p>') !!}
	 </div>

	  <div class="form-group col-md-6 has-feedback{{$errors->has('besar_pinjaman') ? ' has-error' : '' }}">
	 	{{ Form::label('besar_pinjaman', 'Pinjaman') }}
	 	{{ Form::text('besar_pinjaman', null, ['class'=>'form-control', 'placeholder'=> 'Total Pinjaman' , 'readonly'=> 'readonly' , 'id' => 'besar_pinjaman' ]) }}
	 	{!! $errors->first('besar_pinjaman','<p class="help-block">:message</p>') !!}
	 </div>  

	 <div class="form-group col-md-6 has-feedback{{$errors->has('saldo_pinjaman') ? ' has-error' : '' }}">
	 	{{ Form::label('saldo_pinjaman', 'Saldo Pinjaman') }}
	 	{{ Form::text('saldo_pinjaman', null, ['class'=>'form-control', 'placeholder'=> 'Saldo Pinjaman', 'readonly'=> 'readonly' , 'id' => 'saldo_pinjaman' ]) }}
	 	{!! $errors->first('saldo_pinjaman','<p class="help-block">:message</p>') !!}
	 </div>  

	 <div class="form-group col-md-6 has-feedback{{$errors->has('pokok') ? ' has-error' : '' }}">
	 	{{ Form::label('pokok', 'Pokok Bulanan') }}
	 	{{ Form::text('pokok', null, ['class'=>'form-control hitung', 'placeholder'=> 'Pokok Bulanan', 'required'=>'required' , 'id' => 'pokok' ]) }}
	 	{!! $errors->first('pokok','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-6 has-feedback{{$errors->has('bunga') ? ' has-error' : '' }}">
	 	{{ Form::label('bunga', 'Bunga Bulanan') }}
	 	{{ Form::text('bunga', null, ['class'=>'form-control hitung', 'placeholder'=> 'Bunga Bulanan', 'required'=>'required', 'id' => 'bunga' ]) }}
	 	{!! $errors->first('bunga','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-6 has-feedback{{$errors->has('simpanan_wajib') ? ' has-error' : '' }}">
	 	{{ Form::label('simpanan_wajib', 'Simpanan Wajib') }}
	 	{{ Form::text('simpanan_wajib', null, ['class'=>'form-control hitung', 'placeholder'=> 'Simpanan Wajib', 'required'=>'required' , 'id' => 'simpanan_wajib' ]) }}
	 	{!! $errors->first('simpanan_wajib','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-6 has-feedback{{$errors->has('denda') ? ' has-error' : '' }}">
	 	{{ Form::label('denda', 'Denda') }}
	 	{{ Form::text('denda', null, ['class'=>'form-control hitung', 'placeholder'=> 'Denda', 'required'=>'required' , 'id' => 'denda']) }}
	 	{!! $errors->first('denda','<p class="help-block">:message</p>') !!}
	 </div> 

	 <div class="form-group col-md-12 has-feedback{{$errors->has('total') ? ' has-error' : '' }}">
	 	{{ Form::label('total', 'Total') }}
	 	{{ Form::text('total', null, ['class'=>'form-control', 'placeholder'=> 'Total', 'required'=>'required' , 'id' => 'total']) }}
	 	{!! $errors->first('total','<p class="help-block">:message</p>') !!}
	 </div> 
	 <div class="form-group col-md-12" id="tabel_angsuran">
	 </div>
	 @if(isset($angsuran->id) && (auth()->user()->hasRole('superadmin')) && $angsuran->status >= 0 )
	  <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal_validasi') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal_validasi', 'Tanggal Validasi') }}
	 	{{ Form::text('tanggal_validasi', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Validasi', 'required'=>'required', 'id'=> 'tanggal_validasi' ]) }}
	 	{!! $errors->first('tanggal_validasi','<p class="help-block">:message</p>') !!}
	 </div>
	 @endif
	 
	 

</div>


<div class="box-footer">
		@if(!isset($angsuran) or (isset($angsuran) &&  $angsuran->status ==  0 ) )
    		{!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
    	@endif
    	 @if(isset($angsuran->id) && (auth()->user()->hasRole('superadmin')) && $angsuran->status == 0 )
    		<input class="btn btn-primary" type="submit" name="valid" value="Valid">
    	@endif
</div>

<script src="{{ asset('/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('/js/jquerynumber/jquery.number.js') }}"></script>
<script type="text/javascript">
	@if(isset($angsuran->id) && (auth()->user()->hasRole('superadmin')) && $angsuran->status == 0 )
    	$("#tanggal_validasi").val('{{date("d-m-Y")}}');
    @endif
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
	$("#besar_pinjaman").number(true, 0);
	$("#saldo_pinjaman").number(true, 0);

	@if(isset($angsuran->id) )
		@if( $angsuran->status == 1 )
 			$('input[type=text]').attr('readonly', 'readonly');
 			$('input[type=number]').attr('readonly', 'readonly');
 			$('.js-select2').attr('disabled', 'disabled');
 		@endif

 		$(document).ready(function(){
 			var getproyeksiurl = "{{ route('angsuran.viewproyeksi') }}";
			
					$.ajax({
				        url: getproyeksiurl,
				        type: 'GET',
				        dataType: 'JSON',
				        data: 'id_pinjaman=' + $("#id_pinjaman").val() +'&tanggal_transaksi='+$("#tanggal_transaksi").val() ,
				        success: function(data) {
		                     		$("#besar_pinjaman").val(data.pinjaman.nominal);
		                     		$("#saldo_pinjaman").val(data.pinjaman.saldo);
				    	}
					});
 		})
	@endif


	var getpinjamanurl = "{{ route('angsuran.viewpeminjaman') }}";
	$("#id_anggota").change(function(){
		    resetvalue();
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
		                	var html = '<option value="">-- Pilih Pinjaman --</option>';
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
		    resetvalue();
			$.ajax({
		                url: getproyeksiurl,
		                type: 'GET',
		                dataType: 'JSON',
		                data: 'id_pinjaman=' + this.value +'&tanggal_transaksi='+$("#tanggal_transaksi").val() ,
		                beforeSend: function() {
		                    $("#loader").show();		         
		                },
		                success: function(data) {
		                	var html = '<option value="">-- Pilih Angsuran --</option>';
                     		for (var i = 0; i < data.proyeksi.length; i++) {
                     			html += '<option value="'+data.proyeksi[i].id+'">('+data.proyeksi[i].angsuran_ke+") "+data.proyeksi[i].tgl_proyeksi+'</option>'
                     		}
                     		console.log(data.pinjaman.nominal);
                     		$("#besar_pinjaman").val(data.pinjaman.nominal);
                     		$("#saldo_pinjaman").val(data.pinjaman.saldo);
                     		$("#id_proyeksi").html(html);

                     		var tabel_angsuran = '<table class="table table-striped table-hover">';
                     		tabel_angsuran += '<tr><th>No.</th><th>Ke.</th><th>Tgl</th><th>Pokok</th><th>Bunga</th><th>Denda</th></tr>';
                     		for (var j = 0; j < data.angsuran.length; j++) {
                     			tabel_angsuran += '<tr><td>'+data.angsuran[j].no_transaksi+'</td>'+
                     									'<td>'+data.angsuran[j].angsuran_ke+'</td>'+
                     									'<td>'+data.angsuran[j].tanggal_transaksi+'</td>'+
                     									'<td>'+parseFloat(data.angsuran[j].pokok).formatMoney(2, '.', ',')+'</td>'+
                     									'<td>'+parseFloat(data.angsuran[j].bunga).formatMoney(2, '.', ',')+'</td>'+
                     									'<td>'+parseFloat(data.angsuran[j].denda).formatMoney(2, '.', ',')+'</td>'+
                     							   '</tr>';
                     		}
                     		tabel_angsuran += '</table>';
                     		$('#tabel_angsuran').html(tabel_angsuran);

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
		                data: 'id_proyeksi=' + this.value +'&tanggal_transaksi='+$("#tanggal_transaksi").val() ,
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
		$('#besar_pinjaman').number(true, 2, '.', '');
		$('#saldo_pinjaman').number(true, 2, '.', '');
	});

	function resetvalue(){
		$("#angsuran_ke").val( 0);
		$('#pokok').val(0 );
		$('#bunga').val(0 );
		$("#simpanan_wajib").val(0);
		$("#denda").val(0);
		$("#total").val(0);
		$("#besar_pinjaman").val(0);
		$("#saldo_pinjaman").val(0);
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

	 
	$(".hitung").keyup(function(){
    	var total = parseFloat($('#pokok').val()) +parseFloat($('#bunga').val())
		            + parseFloat($('#simpanan_wajib').val()) + parseFloat($('#denda').val())  ;

		$("#total").val(total);                   
	});
	
	
</script>
