<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('code') ? ' has-error' : '' }}">
	 	{{ Form::label('code', 'Kode') }}
	 	{{ Form::text('code', null, ['class'=>'form-control', 'placeholder'=> 'Kode', 'required'=>'required']) }}
	 	{!! $errors->first('code','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('nama') ? ' has-error' : '' }}">
	 	{{ Form::label('nama', 'Nama') }}
	 	{{ Form::text('nama', null, ['class'=>'form-control', 'placeholder'=> 'Nama', 'required'=>'required']) }}
	 	{!! $errors->first('nama','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('group_id') ? ' has-error' : '' }}">
	 	{{ Form::label('group_id', 'Group ID') }}
	 	{!! Form::select('group_id', App\Model\Acc\CoaGroup::pluck('nama','id')->all() , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('group_id','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('parent_id') ? ' has-error' : '' }}">
	 	{{ Form::label('parent_id', 'Parent COA') }}
	 	{!! Form::select('parent_id', $parent , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('parent_id','<p class="help-block">:message</p>') !!}
	 </div>


	  <div class="form-group has-feedback{{$errors->has('op_balance') ? ' has-error' : '' }}">
	 	{{ Form::label('op_balance', 'OPENING Balance') }}
	 	{{ Form::number('op_balance', null, ['class'=>'form-control', 'placeholder'=> 'OPENING BALANCE', 'required'=>'required']) }}
	 	{!! $errors->first('op_balance','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('op_balance_dc') ? ' has-error' : '' }}">
	 	{{ Form::label('op_balance_dc', 'OPENING BALANCE DC') }}
	 	{!! Form::select('op_balance_dc', ['C'=>'Credit', 'D'=>'Debet'], null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('op_balance_dc','<p class="help-block">:message</p>') !!}
	 </div>

	  <div class="form-group has-feedback{{$errors->has('balance_sheet_group') ? ' has-error' : '' }}">
	 	{{ Form::label('balance_sheet_group', 'Balance Sheet Type') }}
	 	{!! Form::select('balance_sheet_group', [0=>'No', 1=>'Yes'], null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('balance_sheet_group','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('balance_sheet_side') ? ' has-error' : '' }}">
	 	{{ Form::label('balance_sheet_side', 'Balance Sheet Type') }}
	 	{!! Form::select('balance_sheet_side', ['C'=>'Credit', 'D'=>'Debet'], null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('balance_sheet_side','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('pls_group') ? ' has-error' : '' }}">
	 	{{ Form::label('pls_group', 'Profit Loss Type') }}
	 	{!! Form::select('pls_group', [0=>'No', 1=>'Yes'], null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('pls_group','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('pls_side') ? ' has-error' : '' }}">
	 	{{ Form::label('pls_side', 'Profit Loss Side') }}
	 	{!! Form::select('pls_side', ['I'=>'Income', 'E'=>'Expense'], null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('pls_side','<p class="help-block">:message</p>') !!}
	 </div>


</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>