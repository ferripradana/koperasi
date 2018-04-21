<?php 
$_notransaksi   = \App\Helpers\Common::getNoTransaksi('pinalti');
$no_transaksi   = isset($pinalti->no_transaksi) ?  $pinalti->no_transaksi : $_notransaksi; 
$anggota_option = ['' => '-- Pilih Anggota --'] + App\Model\Anggota::select(
			          DB::raw("CONCAT(nik,'-',nama) AS name"),'id')
					->pluck('name', 'id')->toArray();
 $id_pinjaman_option = [];
 if (isset($pinalti->id_peminjaman)) {
    	$id_pinjaman_option = App\Model\Peminjaman::where('id',$pinalti->id_peminjaman)->pluck('no_transaksi','id');
 }
 ?>
<div class="box-body">
	<div class="form-group col-md-12 has-feedback{{$errors->has('no_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('no_transaksi', 'No Transaksi') }}
	 	{{ Form::text('no_transaksi', $no_transaksi, ['class'=>'form-control', 'placeholder'=> 'No Transaksi' , 'readonly'=> 'readonly' , 'id' => 'no_transaksi' ]) }}
	 	{!! $errors->first('no_transaksi','<p class="help-block">:message</p>') !!}
	 </div>  

	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_anggota') ? ' has-error' : '' }}">
	 	{{ Form::label('id_anggota', 'Nama Anggota') }}
	 	{!! Form::select('id_anggota', $anggota_option, null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_anggota']) !!}
	 	{!! $errors->first('id_anggota','<p class="help-block">:message</p>') !!}
	 </div>
	 
	 <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal', 'Tanggal') }}
	 	{{ Form::text('tanggal', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal', 'required'=>'required', 'readonly'=>'readonly', 'id'=> 'tanggal' ]) }}
	 	{!! $errors->first('tanggal','<p class="help-block">:message</p>') !!}
	 </div>

	 <div class="form-group col-md-12" id='loader' style='display: none;'>
		<div>
		    <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='50px' height='50px'></center>
		</div>
	 </div>

	 <div class="form-group col-md-12 has-feedback{{$errors->has('id_peminjaman') ? ' has-error' : '' }}">
	 	{{ Form::label('id_peminjaman', 'No. Pinjaman') }}
	 	{!! Form::select('id_peminjaman', $id_pinjaman_option , null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'id_peminjaman']) !!}
	 	{!! $errors->first('id_peminjaman','<p class="help-block">:message</p>') !!}
	 </div>

	 <div class="form-group col-md-6 has-feedback{{$errors->has('besar_pinjaman') ? ' has-error' : '' }}">
	 	{{ Form::label('besar_pinjaman', 'Pinjaman') }}
	 	{{ Form::text('besar_pinjaman', null, ['class'=>'form-control', 'placeholder'=> 'Total Pinjaman' , 'readonly'=> 'readonly' , 'id' => 'besar_pinjaman' ]) }}
	 	{!! $errors->first('besar_pinjaman','<p class="help-block">:message</p>') !!}
	 </div>  

	 <div class="form-group col-md-6 has-feedback{{$errors->has('angsuran_nominal') ? ' has-error' : '' }}">
	 	{{ Form::label('angsuran_nominal', 'Saldo Pinjaman') }}
	 	{{ Form::text('angsuran_nominal', null, ['class'=>'form-control', 'placeholder'=> 'Saldo Pinjaman', 'readonly'=> 'readonly' , 'id' => 'angsuran_nominal' ]) }}
	 	{!! $errors->first('angsuran_nominal','<p class="help-block">:message</p>') !!}
	 </div>  

	 <div class="form-group col-md-6 has-feedback{{$errors->has('tenor') ? ' has-error' : '' }}">
	 	{{ Form::label('tenor', 'Tenor') }}
	 	{{ Form::text('tenor', null, ['class'=>'form-control', 'placeholder'=> 'Tenor' , 'readonly'=> 'readonly' , 'id' => 'tenor' ]) }}
	 	{!! $errors->first('tenor','<p class="help-block">:message</p>') !!}
	 </div>  

	 <div class="form-group col-md-6 has-feedback{{$errors->has('pokok') ? ' has-error' : '' }}">
	 	{{ Form::label('pokok', 'Pokok Bulanan') }}
	 	{{ Form::text('pokok', null, ['class'=>'form-control', 'placeholder'=> 'Pokok' , 'readonly'=> 'readonly' , 'id' => 'pokok' ]) }}
	 	{!! $errors->first('pokok','<p class="help-block">:message</p>') !!}
	 </div>  

	  <div class="form-group col-md-6 has-feedback{{$errors->has('banyak_angsuran') ? ' has-error' : '' }}">
	 	{{ Form::label('banyak_angsuran', 'Kekurangan Tenor') }}
	 	{{ Form::text('banyak_angsuran', null, ['class'=>'form-control', 'placeholder'=> 'Kekurangan Tenor' , 'readonly'=> 'readonly' , 'id' => 'banyak_angsuran' ]) }}
	 	{!! $errors->first('banyak_angsuran','<p class="help-block">:message</p>') !!}
	 </div>  


	 <div class="form-group col-md-6 has-feedback{{$errors->has('nominal') ? ' has-error' : '' }}">
	 	{{ Form::label('nominal', 'Nominal Pinalti') }}
	 	{{ Form::text('nominal', null, ['class'=>'form-control', 'placeholder'=> 'Nominal Pinalti' , 'id' => 'nominal', 'required'=>'required' ]) }}
	 	{!! $errors->first('nominal','<p class="help-block">:message</p>') !!}
	 </div>  

	 <div class="form-group col-md-12 has-feedback{{$errors->has('grand_total') ? ' has-error' : '' }}">
	 	{{ Form::label('grand_total', 'Grand Total') }}
	 	{{ Form::text('grand_total', null, ['class'=>'form-control', 'placeholder'=> 'Grand Total' , 'id' => 'grand_total', 'readonly' => 'readonly' ]) }}
	 	{!! $errors->first('grand_total','<p class="help-block">:message</p>') !!}
	 </div>  

	 <div class="form-group col-md-12" id="tabel_angsuran">
	 </div>

	 <div class="form-group col-md-12 has-feedback{{$errors->has('keterangan') ? ' has-error' : '' }}">
	 	{{ Form::label('keterangan', 'Keterangan') }}
	 	{{ Form::textarea('keterangan', null, ['class'=>'form-control ', 'placeholder'=> 'Keterangan']) }}
	 	{!! $errors->first('Keterangan','<p class="help-block">:message</p>') !!}
	 </div>

	  @if(isset($pinalti->id) && (auth()->user()->hasRole('superadmin')) && $pinalti->status >= 0 )
	  <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal_validasi') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal_validasi', 'Tanggal Validasi') }}
	 	{{ Form::text('tanggal_validasi', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Validasi', 'required'=>'required', 'id'=> 'tanggal_validasi' ]) }}
	 	{!! $errors->first('tanggal_validasi','<p class="help-block">:message</p>') !!}
	 </div>
	 @endif

</div>


<div class="box-footer">
    @if(!isset($pinalti) or (isset($pinalti) &&  $pinalti->status ==  0 ) )
    		{!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
    @endif
    @if(isset($pinalti->id) && (auth()->user()->hasRole('superadmin')) && $pinalti->status == 0 )
    		<input class="btn btn-primary" type="submit" name="valid" value="Valid">
    @endif
</div>

<script src="{{ asset('/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('/js/jquerynumber/jquery.number.js') }}"></script>
<script type="text/javascript">
	$('.date').datepicker({  
	       format: 'dd-mm-yyyy',
	       todayHighlight: true
	     });  
	$("#besar_pinjaman").number(true, 0);
	$("#angsuran_nominal").number(true, 0);
	$("#pokok").number(true, 0);
	$("#nominal").number(true, 0);
	$("#grand_total").number(true, 0);

	$('form').on('submit', function(e) {
	    $('#besar_pinjaman').number(true, 2, '.', '');
		$('#angsuran_nominal').number(true, 2, '.', '');
		$('#pokok').number(true, 2, '.', '');
		$('#nominal').number(true, 2, '.', '');
		$('#grand_total').number(true, 2, '.', '');
	});

	@if(isset($pinalti->id) )
		$(document).ready(function(){
 			var getproyeksiurl = "{{ route('pinalti.viewproyeksi') }}";
			
					$.ajax({
				        url: getproyeksiurl,
				        type: 'GET',
				        dataType: 'JSON',
				        data: 'id_pinjaman=' + $("#id_peminjaman").val() +'&tanggal='+$("#tanggal").val() ,
				        success: function(data) {
		                     		$("#besar_pinjaman").val(data.pinjaman.nominal);
		                     		$("#tenor").val(data.pinjaman.tenor);
		                     		$("#pokok").val(data.pinjaman.cicilan);

		                     		var gt = parseFloat($("#angsuran_nominal").val()) + parseFloat($("#nominal").val()) ;
									$('#grand_total').val(gt);
				    	}
					});
 		});
	@endif


	var getpinjamanurl = "{{ route('angsuran.viewpeminjaman') }}";
	$("#id_anggota").change(function(){
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
                     		$("#id_peminjaman").html(html);
		                   $("#loader").hide();  
		                }
		    });
	});

	$("#nominal").keyup(function(){
		var gt = parseFloat($("#angsuran_nominal").val()) + parseFloat($("#nominal").val()) ;
		$('#grand_total').val(gt);
	});

	var getproyeksiurl = "{{ route('pinalti.viewproyeksi') }}";
	$("#id_peminjaman").change(function(){
		$.ajax({
			url: getproyeksiurl,
		                type: 'GET',
		                dataType: 'JSON',
		                data: 'id_pinjaman=' + this.value +'&tanggal='+$("#tanggal").val() ,
		                beforeSend: function() {
		                    $("#loader").show();		         
		                },
		                success: function(data) {

		                	$("#besar_pinjaman").val(data.pinjaman.nominal);
                     		$("#angsuran_nominal").val(data.pinjaman.saldo);
                     		$("#tenor").val(data.pinjaman.tenor);
                     		$("#pokok").val(data.pinjaman.cicilan);

                     		var banyak_angsuran = parseInt(parseFloat(data.pinjaman.saldo)/ parseFloat(data.pinjaman.cicilan) ) ;

                     		$("#banyak_angsuran").val(banyak_angsuran);
                     		
                     		var persen = (parseFloat(data.pinjaman.nominal) - parseFloat(data.pinjaman.saldo) ) /  parseFloat(data.pinjaman.nominal) * 100 ;
                     		
                     		var pinalti = 0;
                     		if (persen < 25) {
                     			pinalti = 4 * parseFloat(data.pinjaman.bunga_nominal) ;
                     		}else if( persen < 50) {
                     			pinalti = 3 * parseFloat(data.pinjaman.bunga_nominal) ;
                     		}else if(persen<75){
                     			pinalti = 2 * parseFloat(data.pinjaman.bunga_nominal) ;
                     		}else if(persen>= 75 ){
                     			pinalti = 0 ;
                     		}

                     		var gt = parseFloat(data.pinjaman.saldo) + pinalti
                     	
                     		$("#nominal").val(pinalti);
                     		$('#grand_total').val(gt);

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
		                },

		});
	});

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


</script>

