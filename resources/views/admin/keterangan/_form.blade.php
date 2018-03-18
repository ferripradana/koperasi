<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('guna_pinjaman') ? ' has-error' : '' }}">
	 	{{ Form::label('guna_pinjaman', 'Guna Pinjaman') }}
	 	{{ Form::text('guna_pinjaman', null, ['class'=>'form-control', 'placeholder'=> 'Guna Pinjaman', 'required'=>'required']) }}
	 	{!! $errors->first('guna_pinjaman','<p class="help-block">:message</p>') !!}
	 </div>

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>