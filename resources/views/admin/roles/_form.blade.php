<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('name') ? ' has-error' : '' }}">
	 	{{ Form::label('name', 'Name') }}
	 	{{ Form::text('name', null, ['class'=>'form-control', 'placeholder'=> 'Role Name', 'required'=>'required']) }}
	 	{!! $errors->first('name','<p class="help-block">:message</p>') !!}
	 </div>

	 <div class="form-group has-feedback{{$errors->has('display_name') ? ' has-error' : '' }}">
	 	{{ Form::label('display_name', 'Display Name') }}
	 	{{ Form::text('display_name', null, ['class'=>'form-control', 'placeholder'=> 'Role Display Name', 'required'=>'required']) }}
	 	{!! $errors->first('display_name','<p class="help-block">:message</p>') !!}
	 </div>
</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>