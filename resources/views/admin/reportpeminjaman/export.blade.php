<?php 
$anggota_option = ['' => '-- Pilih Anggota --'] + App\Model\Anggota::select(
                      DB::raw("CONCAT(nik,' - ',nama) AS name"),'id')
                          ->pluck('name', 'id')->toArray();

?>
@extends('layouts.app')

@section('dashboard')
   Report Proyeksi Angsuran Pinjaman
   <small>Report Proyeksi Angsuran Pinjaman</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Report Proyeksi Angsuran Pinjaman</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Report Proyeksi Angsuran Pinjaman</h3>
              </div>
                <!-- /.box-header -->
                {!! Form::open(['url' => route('reportpeminjaman.reportpeminjamanpost'), 'method' => 'post', "target"=>"_blank" ]) !!}
                    <div class="box-body">
                        <div class="form-group has-feedback{!! $errors->has('id_anggota') ? 'has-error' : '' !!}">
                            {!! Form::label('id_anggota', 'Anggota') !!}
                            {!! Form::select('id_anggota', $anggota_option, null, [
                                'class' => 'form-control js-select2',
                                ]) !!}
                            {!! $errors->first('id_anggota', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="form-group" id='loader' style='display: none;'>
                            <div>
                                <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='50px' height='50px'></center>
                            </div>
                        </div>
                        <div class="form-group has-feedback{!! $errors->has('id_pinjaman') ? 'has-error' : '' !!}">
                            {!! Form::label('id_pinjaman', 'Pinjaman') !!}
                            {!! Form::select('id_pinjaman', [], null, [
                                'class' => 'form-control js-select2',
                                ]) !!}
                            {!! $errors->first('id_pinjaman', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{!! $errors->has('type') ? 'has-error' : '' !!}">
                            {!! Form::label('type', 'Pilih Output') !!}

                            <div class="radio">
                                <label>
                                    {{ Form::radio('type', 'xls') }} Excel
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('type', 'pdf') }} PDF
                                </label>
                            </div>
                             <div class="radio">
                                <label>
                                    {{ Form::radio('type', 'html', true) }} HTML
                                </label>
                            </div>
                            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        {!! Form::submit('Download', ['class' => 'btn btn-primary']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <script type="text/javascript">
        var getpinjamanurl = "{{ route('reportpeminjaman.viewpeminjaman') }}";
        $("#id_anggota").change(function(){

                $.ajax({
                            url: getpinjamanurl,
                            type: 'GET',
                            dataType: 'JSON',
                            data: 'id_anggota=' + this.value ,
                            beforeSend: function() {
                                $("#loader").show();                 
                            },
                            success: function(data) {
                                var html = '<option>-- Pilih Pinjaman --</option>';
                                for (var i = 0; i < data.length; i++) {
                                    html += '<option value="'+data[i].id+'">'+data[i].no_transaksi+'</option>'
                                }
                                $("#id_pinjaman").html(html);
                               $("#loader").hide();  
                            }
                });
        });
    </script>
@endsection
