<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('name') ? ' has-error' : '' }}">
	 	{{ Form::label('name', 'Name') }}
	 	{{ Form::text('name', null, ['class'=>'form-control', 'placeholder'=> 'Name', 'required'=>'required']) }}
	 	{!! $errors->first('name','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('department_id') ? ' has-error' : '' }}">
	 	{{ Form::label('department_id', 'Department') }}
	 	{!! Form::select('department_id', App\Model\Department::pluck('name','id')->all(), null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('department_id','<p class="help-block">:message</p>') !!}
	 </div>

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>