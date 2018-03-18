<div class="box-body">
	<div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
		{!! Form::label('name', 'Nama') !!}
		{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=> 'Nama', 'required'=>'required']) !!}
		{!! $errors->first('name','<p class="help-block">:message</p>') !!}
	</div>

	<div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::label('email', 'Email') !!}
       	{{  Form::email('email', null, $attributes = array('class'=>'form-control', 'placeholder'=>'Email', 'required'=>'required')) }}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::label('password', 'Password') !!}
        {!! Form::password('password', $attributes = array('class'=>'form-control', 'placeholder'=>'Password', 'required'=>'required'))  !!}
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        {!! Form::label('password_confirmation', 'Password Confirmation') !!}
        {!! Form::password('password_confirmation', $attributes = array('class'=>'form-control', 'placeholder'=>'Password Confirmation', 'required'=>'required'))  !!}
        {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group has-feedback{{ $errors->has('role') ? ' has-error' : '' }}">
        {!! Form::label('role', 'Role') !!}
        {!! Form::select('role', App\Role::pluck('name','id')->all(), null, ['class' => 'form-control', 'required'=>'required']) !!}
        {!! $errors->first('role', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>