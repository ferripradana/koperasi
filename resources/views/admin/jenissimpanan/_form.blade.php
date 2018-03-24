<?php
	$coa = \App\Model\Acc\COA::select(
                    \DB::raw("CONCAT(code,'-',nama) AS name"),'id','parent_id')
                        ->orderBy('code','asc')
                        ->pluck('name', 'id');
?>
<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('nama_simpanan') ? ' has-error' : '' }}">
	 	{{ Form::label('nama_simpanan', 'Nama Simpanan') }}
	 	{{ Form::text('nama_simpanan', null, ['class'=>'form-control', 'placeholder'=> 'Nama Simpanan', 'required'=>'required']) }}
	 	{!! $errors->first('nama_simpanan','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('nominal_minimum') ? ' has-error' : '' }}">
	 	{{ Form::label('nominal_minimum', 'Nominal Minimum') }}
	 	{{ Form::number('nominal_minimum', null, ['class'=>'form-control', 'placeholder'=> 'Nominal Minimum', 'required'=>'required']) }}
	 	{!! $errors->first('nominal_minimum','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('peminjaman_debit_coa') ? ' has-error' : '' }}">
	 	{{ Form::label('peminjaman_debit_coa', 'Akun Simpanan Debet') }}
	 	{!! Form::select('peminjaman_debit_coa', $coa , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('peminjaman_debit_coa','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('peminjaman_credit_coa') ? ' has-error' : '' }}">
	 	{{ Form::label('peminjaman_credit_coa', 'Akun Simpanan Credit') }}
	 	{!! Form::select('peminjaman_credit_coa', $coa , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('peminjaman_credit_coa','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('pengambilan_debit_coa') ? ' has-error' : '' }}">
	 	{{ Form::label('pengambilan_debit_coa', 'Akun Pengambilan Debet') }}
	 	{!! Form::select('pengambilan_debit_coa', $coa , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('pengambilan_debit_coa','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('pengambilan_credit_coa') ? ' has-error' : '' }}">
	 	{{ Form::label('pengambilan_credit_coa', 'Akun Pengambilan Credit') }}
	 	{!! Form::select('pengambilan_credit_coa', $coa , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('pengambilan_credit_coa','<p class="help-block">:message</p>') !!}
	 </div>

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>