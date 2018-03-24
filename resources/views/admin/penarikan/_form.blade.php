<?php 
	$_notransaksi = "PENR".date("dmY").sprintf("%07d", \App\Model\Penarikan::count('id') + 1 );
	$no_transaksi = isset( $penarikan->no_transaksi ) ?  $penarikan->no_transaksi :$_notransaksi; 
?>
<div class="box-body">
	 <div class="form-group col-md-6 has-feedback{{$errors->has('no_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('no_transaksi', 'No. Transaksi') }}
	 	{{ Form::text('no_transaksi', $no_transaksi, ['class'=>'form-control', 'placeholder'=> 'No. Transaksi', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
	 	{!! $errors->first('no_transaksi','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal_transaksi', 'Tanggal Transaksi') }}
	 	{{ Form::text('tanggal_transaksi', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Transaksi', 'required'=>'required', 'readonly'=>'readonly', 'id' => 'tanggal_transaksi']) }}
	 	{!! $errors->first('tanggal_transaksi','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group col-md-6 has-feedback{{$errors->has('id_anggota') ? ' has-error' : '' }}">
	 	{{ Form::label('id_anggota', 'Nama Anggota') }}
	 	{!! Form::select('id_anggota', $anggota = [''=>'-- Pilih Anggota --'] + App\Model\Anggota::pluck('nama','id')->all(), null, ['class' => 'form-control js-select2', 'required'=>'required']) !!}
	 	{!! $errors->first('id_anggota','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_simpanan') ? ' has-error' : '' }}">
	 	{{ Form::label('id_simpanan', 'Nama Simpanan') }}
	 	{!! Form::select('id_simpanan', $jenissimpanan = App\Model\JenisSimpanan::where('nama_simpanan','like','%sukarela%')->pluck('nama_simpanan','id'), null, ['class' => 'form-control js-select2', 'id'=> 'id_simpanan' ]) !!}
	 	{!! $errors->first('id_simpanan','<p class="help-block">:message</p>') !!}
	 </div>
	 <!-- Image loader -->
	 <div class="form-group col-md-12" id='loader' style='display: none;'>
		<div>
		    <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='50px' height='50px'></center>
		</div>
	</div>	
	<!-- Image loader -->  
	 <div class="form-group col-md-12" id="saldo">
	 	
	 </div>
	 <div class="form-group col-md-12 has-feedback{{$errors->has('nominal') ? ' has-error' : '' }}">
	 	{{ Form::label('nominal', 'Nominal') }}
	 	{{ Form::number('nominal', null, ['class'=>'form-control ', 'placeholder'=> 'Nominal', 'required'=>'required', 'min'=> 10000, 'id'=> 'nominal']) }}
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
<script type="text/javascript">
    $('.date').datepicker({  
       format: 'dd-mm-yyyy',
       todayHighlight: true
     });  

    var today = "{{ date('d-m-Y') }}";
    $("#tanggal_transaksi").val(today);


    $('#id_anggota').change(function() {
    	var url = "{{ route('penarikan.viewsaldo') }}";
    	$("#saldo").html('');
    	$.ajax({
    		url: url,
                type: 'GET',
                dataType: 'JSON',
                data: 'id_anggota='+ this.value+'&id_simpanan='+ $('#id_simpanan').val() ,
                beforeSend: function() {
                    $("#loader").show();
                },
                success: function(data) {
                	var saldo = "<h4> <b>Saldo : " + data.saldo.formatMoney(2, ',', '.') + '</b></h4>';
                	$("#saldo").html(saldo)
                	$("#nominal").attr({
       					"max" : data.saldo,        
    				});
                	$("#loader").hide();
                }
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

    });
</script>
