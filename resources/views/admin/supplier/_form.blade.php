<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('kode_supplier') ? ' has-error' : '' }}">
	 	{{ Form::label('kode_supplier', 'Kode Supplier') }}
	 	{{ Form::text('kode_supplier', null, ['class'=>'form-control', 'placeholder'=> 'Kode Supplier', 'required'=>'required']) }}
	 	{!! $errors->first('kode_supplier','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('nama') ? ' has-error' : '' }}">
	 	{{ Form::label('nama', 'Nama') }}
	 	{{ Form::text('nama', null, ['class'=>'form-control', 'placeholder'=> 'Name', 'required'=>'required']) }}
	 	{!! $errors->first('nama','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('alamat') ? ' has-error' : '' }}">
	 	{{ Form::label('alamat', 'Alamat') }}
	 	{{ Form::text('alamat', null, ['class'=>'form-control', 'placeholder'=> 'Alamat', 'required'=>'required']) }}
	 	{!! $errors->first('alamat','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('id_bank') ? ' has-error' : '' }}">
	 	{{ Form::label('id_bank', 'Bank') }}
	 	{!! Form::select('id_bank', 	App\Model\Bank::pluck('name','id')->all(), null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('id_bank','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('nama_rekening') ? ' has-error' : '' }}">
	 	{{ Form::label('nama_rekening', 'Nama Rekening') }}
	 	{{ Form::text('nama_rekening', null, ['class'=>'form-control', 'placeholder'=> 'Nama Rekening', 'required'=>'required']) }}
	 	{!! $errors->first('nama_rekening','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('no_rekening') ? ' has-error' : '' }}">
	 	{{ Form::label('no_rekening', 'No Rekening') }}
	 	{{ Form::text('no_rekening', null, ['class'=>'form-control', 'placeholder'=> 'No Rekening', 'required'=>'required']) }}
	 	{!! $errors->first('no_rekening','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('phone') ? ' has-error' : '' }}">
	 	{{ Form::label('phone', 'Phone') }}
	 	{{ Form::text('phone', null, ['class'=>'form-control', 'placeholder'=> 'Phone', 'required'=>'required']) }}
	 	{!! $errors->first('phone','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('status') ? ' has-error' : '' }}">
	 	{{ Form::label('status', 'Status') }}
	 	{!! Form::select('status', 	$status = [1=>'PKP', 2=>"Non PKP"] , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('status','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('status_beli') ? ' has-error' : '' }}">
	 	{{ Form::label('status_beli', 'Status Beli') }}
	 	{!! Form::select('status_beli', 	$status_belil = [1=>'Konsinyasi', 2=>"Tempo", 3=>"Tunai"] , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('status_beli','<p class="help-block">:message</p>') !!}
	 </div>
</div>

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>