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

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>