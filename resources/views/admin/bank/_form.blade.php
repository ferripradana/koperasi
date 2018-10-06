<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('name') ? ' has-error' : '' }}">
	 	{{ Form::label('name', 'Name') }}
	 	{{ Form::text('name', null, ['class'=>'form-control', 'placeholder'=> 'Bank Name', 'required'=>'required']) }}
	 	{!! $errors->first('name','<p class="help-block">:message</p>') !!}
	 </div>

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>