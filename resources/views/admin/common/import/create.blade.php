@extends('layouts.app')

@section('dashboard')
    Import {{$type}}
@endsection

<?php 
    switch ($type) {
        case 'anggota':
            $master = 'anggotas';
            break;
        case 'department':
            $master = 'departments';
            break;    
        case 'unit':
            $master = 'units';
            break;    
         case 'jabatan':
            $master = 'jabatan';
            break;
        case 'keteranganpinjaman':
            $master = 'keteranganpinjaman';
            break;        
    }

 ?>


@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Master</a></li>
    <li><a href="{{url('/admin/master/'.$master)}}">{{$type}}</a></li>
    <li class="active">Import {{$type}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Import {{$type}}</h3>
                    <div class="col-md-12">
                                <?php $url = url("file/".$type.".csv") ?>
                                Jenis file yang diizinkan: CSV, XLS. Silahkan <a href="{{$url}}"><strong>download</strong></a> contoh berkas.
                    </div>
                    {{ Form::open(['url'=> route($type.'.importaction'), 'method'=>'post', 'files'=>'true' ]) }}
                        <div class="box-body">
                         <div class="form-group has-feedback{{$errors->has('import') ? ' has-error' : '' }}">
                          {!! Form::label('import', "Import CSV", ['class' => 'control-label']) !!}
                          {!! Form::file('import', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('import', '<p class="help-block">:message</p>') !!}
                          </div>
                        </div>
                        <div class="box-footer">
                            {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection


