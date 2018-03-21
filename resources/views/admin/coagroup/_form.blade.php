<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('code') ? ' has-error' : '' }}">
	 	{{ Form::label('code', 'Kode') }}
	 	{{ Form::text('code', null, ['class'=>'form-control', 'placeholder'=> 'Kode', 'required'=>'required']) }}
	 	{!! $errors->first('code','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('nominal_minimum') ? ' has-error' : '' }}">
	 	{{ Form::label('nama', 'Nama') }}
	 	{{ Form::text('nama', null, ['class'=>'form-control', 'placeholder'=> 'Nama', 'required'=>'required']) }}
	 	{!! $errors->first('nama','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('description') ? ' has-error' : '' }}">
	 	{{ Form::label('description', 'Deskripsi') }}
	 	{{ Form::text('description', null, ['class'=>'form-control', 'placeholder'=> 'Deskripsi', 'required'=>'required']) }}
	 	{!! $errors->first('description','<p class="help-block">:message</p>') !!}
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