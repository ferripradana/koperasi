<?php
	$coa = \App\Model\Acc\COA::select(
                    \DB::raw("CONCAT(code,'-',nama) AS name"),'id','parent_id')
                        ->orderBy('code','asc')
                        ->pluck('name', 'id');
?>
<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('nama_transaksi') ? ' has-error' : '' }}">
	 	{{ Form::label('nama_transaksi', 'Nama Transaksi') }}
	 	{{ Form::text('nama_transaksi', null, ['class'=>'form-control', 'placeholder'=> 'Nama Transaksi', 'required'=>'required']) }}
	 	{!! $errors->first('nama_transaksi','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('type') ? ' has-error' : '' }}">
	 	{{ Form::label('type', 'Type') }}
	 	{!! Form::select('type', [1 => 'Pendapatan', 2=>'Beban', 3=>'Pembelian Dagang Kontan',
	 	4=>'Pembelian Dagang Non Kontan', 5=>'Pembayaran Dagang Non Kontan', 6=>'Retur Kontan', 7=>'Retur Non Kontan' ] , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('type','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('debit_coa') ? ' has-error' : '' }}">
	 	{{ Form::label('debit_coa', 'Akun Debet') }}
	 	{!! Form::select('debit_coa', $coa , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('debit_coa','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('credit_coa') ? ' has-error' : '' }}">
	 	{{ Form::label('credit_coa', 'Akun Credit') }}
	 	{!! Form::select('credit_coa', $coa , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('credit_coa','<p class="help-block">:message</p>') !!}
	 </div>
	

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>