<?php 
	$_notransaksi = \App\Helpers\Common::getNoTransaksi('transaksi_unit');
	$no_transaksi = isset( $transaksi->no_transaksi ) ?  $transaksi->no_transaksi :$_notransaksi; 
?>
<div class="box-body">
	 <div class="form-group col-md-6 has-feedback{{$errors->has('no_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('no_transaksi', 'No. Transaksi') }}
	 	{{ Form::text('no_transaksi', $no_transaksi, ['class'=>'form-control', 'placeholder'=> 'Name', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
	 	{!! $errors->first('no_transaksi','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal', 'Tanggal Transaksi') }}
	 	{{ Form::text('tanggal', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Transaksi', 'required'=>'required', 'readonly'=>'readonly']) }}
	 	{!! $errors->first('tanggal','<p class="help-block">:message</p>') !!}
	 </div>
	 <!-- Image loader -->
	 <div class="form-group col-md-12" id='loader' style='display: none;'>
		<div>
		    <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='50px' height='50px'></center>
		</div>
	</div>	
	<!-- Image loader -->  
	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_jenis_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('id_jenis_transaksi', 'Jenis Transaksi') }}
	 	{!! Form::select('id_jenis_transaksi', $jenissimpanan = [''=>'-- Pilih Jenis Transaksi --']	+ App\Model\JenisTransaksi::pluck('nama_transaksi','id')->all(), null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('id_jenis_transaksi','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('type') ? ' has-error' : '' }}">
	 	{{ Form::label('type', 'Type Transaksi') }}
	 	{!! Form::select('type', ['1'=>'Pendapatan','2'=>'Beban' ] , null, ['class' => 'form-control','id'=>'type' ]) !!}
	 	{!! $errors->first('type','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('id_unit') ? ' has-error' : '' }}">
	 	{{ Form::label('id_unit', 'Unit') }}
	 	{!! Form::select('id_unit', $unit = App\Model\Unit::pluck('name','id')->all(), null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('id_unit','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group col-md-6 has-feedback{{$errors->has('id_supplier') ? ' has-error' : '' }}">
	 	{{ Form::label('id_supplier', 'Supplier') }}
	 	{!! Form::select('id_supplier', $supplier = [''=>'-- Pilih Supplier --'] +  App\Model\Supplier::pluck('nama','id')->all(), null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('id_supplier','<p class="help-block">:message</p>') !!}
	 </div>

	 <div class="form-group col-md-6 has-feedback{{$errors->has('nama_bank') ? ' has-error' : '' }}">
	 	{{ Form::label('nama_bank', 'Bank') }}
	 	{{ Form::text('nama_bank', null, ['class'=>'form-control', 'placeholder'=> 'Bank' ]) }}
	 	{!! $errors->first('nama_bank','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('no_ref') ? ' has-error' : '' }}">
	 	{{ Form::label('no_ref', 'No. Ref') }}
	 	{{ Form::text('no_ref', null, ['class'=>'form-control', 'placeholder'=> 'No. Ref' ]) }}
	 	{!! $errors->first('no_ref','<p class="help-block">:message</p>') !!}
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

    var today = '{{ date("d-m-Y") }}';
    $('#tanggal').val(today);

    $('#nominal').number( true, 0 );

    $('form').on('submit', function(e) {
	    $('#nominal').number(true, 2, '.', '');
	 });

    $("#id_jenis_transaksi").change(function(){
    	var val = this.value;
    	var url = "{{ url('/admin/master/jenistransaksi') }}/"+ val;
    	$.ajax({
    		    url: url,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function() {
                    $("#loader").show();
                },
                success: function(data) {
                	//console.log(data);
                	//$('select').select2().trigger('change');
                	$("#type").val(data.type);
                	$("#loader").hide();
                }
    	});
    });

    $("#id_supplier").change(function(){
    	var val = this.value;
    	if(val<1) {
    		val = 0;
    		$("#bank").val("");
    		return;
    	}
    	var url = "{{ url('/admin/master/supplier') }}/"+ val;
    	$.ajax({
    		    url: url,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function() {
                    $("#loader").show();
                },
                success: function(data) {
                	//console.log(data);
                	//$('select').select2().trigger('change');
                	$("#nama_bank").val(data.bank.name);
                	$("#loader").hide();
                }
    	});
    });
</script>  