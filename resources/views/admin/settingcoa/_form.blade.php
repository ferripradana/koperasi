<?php
	$coa = \App\Model\Acc\COA::select(
                    \DB::raw("CONCAT(code,'-',nama) AS name"),'id','parent_id')
                        ->orderBy('code','asc')
                        ->pluck('name', 'id');

    $pem_d = isset($peminjaman_debit->id_coa) ? $peminjaman_debit->id_coa : null  ;
 	$pem_c = isset($peminjaman_credit->id_coa) ? $peminjaman_credit->id_coa : null  ;

 	$ang_d = isset($angsuran_debit->id_coa) ? $angsuran_debit->id_coa : null  ;
 	$ang_c = isset($angsuran_credit->id_coa) ? $angsuran_credit->id_coa : null  ;

 	$b_d = isset($bunga_debit->id_coa) ? $bunga_debit->id_coa : null  ;
 	$b_c = isset($bunga_credit->id_coa) ? $bunga_credit->id_coa : null  ;

 	$d_d = isset($denda_debit->id_coa) ? $denda_debit->id_coa : null  ;
 	$d_c = isset($denda_credit->id_coa) ? $denda_credit->id_coa : null  ;

 	$p_d = isset($pinalti_debit->id_coa) ? $pinalti_debit->id_coa : null  ;
 	$p_c = isset($pinalti_credit->id_coa) ? $pinalti_credit->id_coa : null  ;


?>

<div class="box-body">
	  <div class="form-group col-md-6 has-feedback{{$errors->has('peminjaman_debit') ? ' has-error' : '' }}">
	 	{{ Form::label('peminjaman_debit', 'Peminjaman DR') }}
	 	{!! Form::select('peminjaman_debit', $coa, $pem_d, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'peminjaman_debit']) !!}
	 	{!! $errors->first('peminjaman_debit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('peminjaman_credit') ? ' has-error' : '' }}">
	 	{{ Form::label('peminjaman_credit', 'Peminjaman CR') }}
	 	{!! Form::select('peminjaman_credit', $coa, $pem_c, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'peminjaman_credit']) !!}
	 	{!! $errors->first('peminjaman_credit','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group col-md-6 has-feedback{{$errors->has('angsuran_debit') ? ' has-error' : '' }}">
	 	{{ Form::label('angsuran_debit', 'Angsuran DR') }}
	 	{!! Form::select('angsuran_debit', $coa, $ang_d, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'angsuran_debit']) !!}
	 	{!! $errors->first('angsuran_debit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('angsuran_credit') ? ' has-error' : '' }}">
	 	{{ Form::label('angsuran_credit', 'Angsuran CR') }}
	 	{!! Form::select('angsuran_credit', $coa, $ang_c, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'angsuran_credit']) !!}
	 	{!! $errors->first('angsuran_credit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('bunga_debit') ? ' has-error' : '' }}">
	 	{{ Form::label('bunga_debit', 'Bunga DR') }}
	 	{!! Form::select('bunga_debit', $coa, $b_d , ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'bunga_debit']) !!}
	 	{!! $errors->first('bunga_debit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('bunga_credit') ? ' has-error' : '' }}">
	 	{{ Form::label('bunga_credit', 'Bunga CR') }}
	 	{!! Form::select('bunga_credit', $coa, $b_c , ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'bunga_credit']) !!}
	 	{!! $errors->first('bunga_credit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('denda_debit') ? ' has-error' : '' }}">
	 	{{ Form::label('denda_debit', 'Denda DR') }}
	 	{!! Form::select('denda_debit', $coa, $d_d , ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'denda_debit']) !!}
	 	{!! $errors->first('denda_debit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('denda_credit') ? ' has-error' : '' }}">
	 	{{ Form::label('denda_credit', 'Denda CR') }}
	 	{!! Form::select('denda_credit', $coa, $d_c , ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'denda_credit']) !!}
	 	{!! $errors->first('denda_credit','<p class="help-block">:message</p>') !!}
	 </div>

	  <div class="form-group col-md-6 has-feedback{{$errors->has('pinalti_debit') ? ' has-error' : '' }}">
	 	{{ Form::label('pinalti_debit', 'Pinalti DR') }}
	 	{!! Form::select('pinalti_debit', $coa, $p_d , ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'pinalti_debit']) !!}
	 	{!! $errors->first('pinalti_debit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('pinalti_credit') ? ' has-error' : '' }}">
	 	{{ Form::label('pinalti_credit', 'Pinalti CR') }}
	 	{!! Form::select('pinalti_credit', $coa, $p_c , ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'pinalti_credit']) !!}
	 	{!! $errors->first('pinalti_credit','<p class="help-block">:message</p>') !!}
	 </div>
</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>