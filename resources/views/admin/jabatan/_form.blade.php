<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('nama_jabatan') ? ' has-error' : '' }}">
	 	{{ Form::label('nama_jabatan', 'Nama Jabatan') }}
	 	{{ Form::text('nama_jabatan', null, ['class'=>'form-control', 'placeholder'=> 'Nama Jabatan', 'required'=>'required']) }}
	 	{!! $errors->first('nama_jabatan','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('plafon') ? ' has-error' : '' }}">
	 	{{ Form::label('plafon', 'Plafon') }}
	 	{{ Form::number('plafon', null, ['class'=>'form-control', 'placeholder'=> 'Plafon', 'required'=>'required']) }}
	 	{!! $errors->first('plafon','<p class="help-block">:message</p>') !!}
	 </div>

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>