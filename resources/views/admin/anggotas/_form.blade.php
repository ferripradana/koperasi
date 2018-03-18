<?php 
	$_nia = sprintf("%06d", \App\Model\Anggota::max('id') + 1 );
	$nia = isset( $anggota->nia ) ?  $anggota->nia :$_nia; 
?>
<div class="box-body">
	 <div class="form-group has-feedback{{$errors->has('nama') ? ' has-error' : '' }}">
	 	{{ Form::label('nama', 'Nama') }}
	 	{{ Form::text('nama', null, ['class'=>'form-control', 'placeholder'=> 'Name', 'required'=>'required']) }}
	 	{!! $errors->first('nama','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('nia') ? ' has-error' : '' }}">
	 	{{ Form::label('nia', 'Nomer Induk Anggota') }}
	 	{{ Form::text('nia', $nia, ['class'=>'form-control', 'placeholder'=> 'Nomer Induk Anggota', 'required'=>'required', 'readonly'=>'readonly']) }}
	 	{!! $errors->first('nia','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('nik') ? ' has-error' : '' }}">
	 	{{ Form::label('nik', 'Nomer Induk Karyawan') }}
	 	{{ Form::text('nik', null, ['class'=>'form-control', 'placeholder'=> 'Nomer Induk Karyawan']) }}
	 	{!! $errors->first('nik','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('no_ktp') ? ' has-error' : '' }}">
	 	{{ Form::label('no_ktp', 'Nomer KTP') }}
	 	{{ Form::text('no_ktp', null, ['class'=>'form-control', 'placeholder'=> 'Nomer KTP', 'required'=>'required']) }}
	 	{!! $errors->first('no_ktp','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('tempat_lahir') ? ' has-error' : '' }}">
	 	{{ Form::label('tempat_lahir', 'Tempat Lahir') }}
	 	{{ Form::text('tempat_lahir', null, ['class'=>'form-control', 'placeholder'=> 'Tempat Lahir', 'required'=>'required']) }}
	 	{!! $errors->first('tempat_lahir','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('tanggal_lahir') ? ' has-error' : '' }}">
	 	{{ Form::label('tanggal_lahir', 'Tanggal Lahir') }}
	 	{{ Form::text('tanggal_lahir', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Lahir', 'required'=>'required', 'readonly'=>'readonly']) }}
	 	{!! $errors->first('tanggal_lahir','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('alamat') ? ' has-error' : '' }}">
	 	{{ Form::label('alamat', 'Alamat') }}
	 	{{ Form::text('alamat', null, ['class'=>'form-control', 'placeholder'=> 'Alamat', 'required'=>'required']) }}
	 	{!! $errors->first('alamat','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('kecamatan') ? ' has-error' : '' }}">
	 	{{ Form::label('kecamatan', 'Kecamatan') }}
	 	{{ Form::text('kecamatan', null, ['class'=>'form-control', 'placeholder'=> 'Kecamatan', 'required'=>'required']) }}
	 	{!! $errors->first('kecamatan','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('kabupaten') ? ' has-error' : '' }}">
	 	{{ Form::label('kabupaten', 'Kabupaten') }}
	 	{{ Form::text('kabupaten', null, ['class'=>'form-control', 'placeholder'=> 'Kabupaten', 'required'=>'required']) }}
	 	{!! $errors->first('kabupaten','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('unit_kerja') ? ' has-error' : '' }}">
	 	{{ Form::label('unit_kerja', 'Unit Kerja') }}
	 	{!! Form::select('unit_kerja', 	App\Model\Unit::pluck('name','id')->all(), null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('unit_kerja','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('tahun_masuk') ? ' has-error' : '' }}">
	 	{{ Form::label('tahun_masuk', 'Tahun Masuk') }}
	 	{{ Form::text('tahun_masuk', null, ['class'=>'form-control', 'placeholder'=> 'Tahun Masuk', 'required'=>'required']) }}
	 	{!! $errors->first('tahun_masuk','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('unit_kerja') ? ' has-error' : '' }}">
	 	{{ Form::label('pengampu', 'Pengampu') }}
	 	{!! Form::select('pengampu', 	$unit = [''=>'-'] + App\Model\Anggota::pluck('nama','id')->all(), null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('pengampu','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('nama_bank') ? ' has-error' : '' }}">
	 	{{ Form::label('nama_bank', 'Nama Bank') }}
	 	{{ Form::text('nama_bank', null, ['class'=>'form-control', 'placeholder'=> 'Nama Bank', 'required'=>'required']) }}
	 	{!! $errors->first('nama_bank','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('nomor_rekening') ? ' has-error' : '' }}">
	 	{{ Form::label('nomor_rekening', 'Nomor Rekening') }}
	 	{{ Form::text('nomor_rekening', null, ['class'=>'form-control', 'placeholder'=> 'Nomor Rekening', 'required'=>'required']) }}
	 	{!! $errors->first('nomor_rekening','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('phone') ? ' has-error' : '' }}">
	 	{{ Form::label('phone', 'Phone') }}
	 	{{ Form::text('phone', null, ['class'=>'form-control', 'placeholder'=> 'Phone', 'required'=>'required']) }}
	 	{!! $errors->first('phone','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{$errors->has('jabatan') ? ' has-error' : '' }}">
	 	{{ Form::label('jabatan', 'Jabatan') }}
	 	{!! Form::select('jabatan', 	$jabatan = [''=>'-'] +  App\Model\Jabatan::pluck('nama_jabatan','id')->all() , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('jabatan','<p class="help-block">:message</p>') !!}
	 </div>
	  <div class="form-group has-feedback{{$errors->has('status') ? ' has-error' : '' }}">
	 	{{ Form::label('status', 'Status') }}
	 	{!! Form::select('status', 	$status = [1=>'Aktiv', 2=>"Tidak Aktiv"] , null, ['class' => 'form-control js-select2']) !!}
	 	{!! $errors->first('status','<p class="help-block">:message</p>') !!}
	 </div>
	 <div class="form-group has-feedback{{ $errors->has('foto') ? ' has-error' : '' }}">
        {!! Form::label('foto', 'Foto') !!}

        {!! Form::file('foto') !!}
        @if (isset($anggota) && $anggota->foto)
            <p> {!! Html::image(asset('img/'.$anggota->foto), null, ['class' => 'img-rounded img-responsive']) !!} </p>
        @endif
        <p class="help-block">Size file (JPG/JPEG/PNG/GIF) maks 1MB</p>
        {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
    </div>

	  

</div>


<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>

<script src="{{ asset('/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript">
    $('.date').datepicker({  
       format: 'dd-mm-yyyy'
     });  
</script>  