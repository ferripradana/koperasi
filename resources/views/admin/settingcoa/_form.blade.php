<?php
	$coa = \App\Model\Acc\COA::select(
                    \DB::raw("CONCAT(code,'-',nama) AS name"),'id','parent_id')
                        ->orderBy('code','asc')
                        ->pluck('name', 'id');

    $pem_d = isset($peminjaman_debit->id_coa) ? $peminjaman_debit->id_coa : null  ;
 	$pem_c = isset($peminjaman_credit->id_coa) ? $peminjaman_credit->id_coa : null  ;


?>

<div class="box-body">
	  <div class="form-group col-md-6 has-feedback{{$errors->has('peminjaman_debit') ? ' has-error' : '' }}">
	 	{{ Form::label('peminjaman_debit', 'Peminjaman DR') }}
	 	{!! Form::select('peminjaman_debit', $coa, $pem_d, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'peminjaman_credit']) !!}
	 	{!! $errors->first('peminjaman_debit','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group col-md-6 has-feedback{{$errors->has('peminjaman_credit') ? ' has-error' : '' }}">
	 	{{ Form::label('peminjaman_credit', 'Peminjaman CR') }}
	 	{!! Form::select('peminjaman_credit', $coa, $pem_c, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=> 'peminjaman_credit']) !!}
	 	{!! $errors->first('peminjaman_credit','<p class="help-block">:message</p>') !!}
	 </div>
</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>