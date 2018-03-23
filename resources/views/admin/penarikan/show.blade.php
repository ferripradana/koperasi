@extends('layouts.app')

@section('dashboard')
   View Transaksi Penarikan
   <small>View Transaksi Penarikan</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Income</a></li>
   <li><a href="{{ url('/admin/transaction/penarikan') }}">Penarikan</a></li>
   <li class="active">View Transaksi Penarikan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">View Penarikan</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($penarikan, ['url' => route('penarikan.update', $penarikan->id), 'method' => 'put']) !!}
                    <?php 
                      $_notransaksi = "PENR".date("dmY").sprintf("%07d", \App\Model\Penarikan::count('id') + 1 );
                      $no_transaksi = isset( $penarikan->no_transaksi ) ?  $penarikan->no_transaksi :$_notransaksi; 
                    ?>
                    <div class="box-body">
                       <div class="form-group col-md-6 has-feedback{{$errors->has('nama') ? ' has-error' : '' }}">
                        {{ Form::label('no_transaksi', 'No. Transaksi') }}
                        {{ Form::text('no_transaksi', $no_transaksi, ['class'=>'form-control', 'placeholder'=> 'Name', 'required'=>'required', 'readonly'=> 'readonly' ]) }}
                        {!! $errors->first('no_transaksi','<p class="help-block">:message</p>') !!}
                       </div>
                       <div class="form-group col-md-6 has-feedback{{$errors->has('tanggal_transaksi') ? ' has-error' : '' }}">
                        {{ Form::label('tanggal_transaksi', 'Tanggal Transaksi') }}
                        {{ Form::text('tanggal_transaksi', null, ['class'=>'form-control date', 'placeholder'=> 'Tanggal Transaksi', 'required'=>'required', 'readonly'=>'readonly', 'id' => 'tanggal_transaksi']) }}
                        {!! $errors->first('tanggal_transaksi','<p class="help-block">:message</p>') !!}
                       </div>
                        <div class="form-group col-md-6 has-feedback{{$errors->has('id_anggota') ? ' has-error' : '' }}">
                        {{ Form::label('id_anggota', 'Nama Anggota') }}
                        {!! Form::select('id_anggota', $anggota = [''=>'-- Pilih Anggota --'] + App\Model\Anggota::pluck('nama','id')->all(), null, ['class' => 'form-control js-select2', 'required'=>'required',  'disabled'=> 'disabled']) !!}
                        {!! $errors->first('id_anggota','<p class="help-block">:message</p>') !!}
                       </div>
                       <div class="form-group col-md-6 has-feedback{{$errors->has('id_simpanan') ? ' has-error' : '' }}">
                        {{ Form::label('id_simpanan', 'Nama Simpanan') }}
                        {!! Form::select('id_simpanan', $jenissimpanan = App\Model\JenisSimpanan::where('nama_simpanan','like','%sukarela%')->pluck('nama_simpanan','id'), null, ['class' => 'form-control js-select2', 'id'=> 'id_simpanan' ,  'disabled'=> 'disabled']) !!}
                        {!! $errors->first('id_simpanan','<p class="help-block">:message</p>') !!}
                       </div>
                       <!-- Image loader -->
                       <div class="form-group col-md-12" id='loader' style='display: none;'>
                        <div>
                            <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='50px' height='50px'></center>
                        </div>
                      </div>  
                      <!-- Image loader -->  
                       <div class="form-group col-md-12" id="saldo">
                        
                       </div>
                       <div class="form-group col-md-12 has-feedback{{$errors->has('nominal') ? ' has-error' : '' }}">
                        {{ Form::label('nominal', 'Nominal') }}
                        {{ Form::number('nominal', null, ['class'=>'form-control ', 'placeholder'=> 'Nominal', 'required'=>'required', 'min'=> 10000, 'id'=> 'nominal',  'readonly'=> 'readonly']) }}
                        {!! $errors->first('nominal','<p class="help-block">:message</p>') !!}
                       </div>
                       <div class="form-group col-md-12 has-feedback{{$errors->has('keterangan') ? ' has-error' : '' }}">
                        {{ Form::label('keterangan', 'Keterangan') }}
                        {{ Form::textarea('keterangan', null, ['class'=>'form-control ', 'placeholder'=> 'Description',  'readonly'=> 'readonly']) }}
                        {!! $errors->first('keterangan','<p class="help-block">:message</p>') !!}
                       </div>

                    </div>
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection