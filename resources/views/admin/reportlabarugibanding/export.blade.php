@extends('layouts.app')

@section('dashboard')
   Laporan Perbandingan Laba Rugi
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Laporan Perbandingan Laba Rugi</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Report Perbandingan Laba Rugi</h3>
              </div>
                <!-- /.box-header -->
                {!! Form::open(['url' => route('labarugibanding.post'), 'method' => 'post', "target"=>"_blank" ]) !!}
                    <div class="box-body">
                        <div class="form-grouphas-feedback{!! $errors->has('bulan') ? 'has-error' : '' !!}">
                            {!! Form::label('bulan_from', 'Bulan I') !!}
                            {!! Form::select('bulan_from', $bulan_option, null, [
                                'class' => 'form-control js-select2 bulan', 'id'=>'bulan'
                                ]) !!}
                            {!! $errors->first('bulan_from', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{!! $errors->has('tahun') ? 'has-error' : '' !!}">
                            {!! Form::label('tahun_from', 'Tahun I') !!}
                            {!! Form::select('tahun_from', $tahun_option, null, [
                                'class' => 'form-control js-select2 tahun', 'id'=> 'tahun'
                                ]) !!}
                            {!! $errors->first('tahun_from', '<p class="help-block">:message</p>') !!}
                        </div>


                        <div class="form-grouphas-feedback{!! $errors->has('bulan') ? 'has-error' : '' !!}">
                            {!! Form::label('bulan_to', 'Bulan II') !!}
                            {!! Form::select('bulan_to', $bulan_option, null, [
                                'class' => 'form-control js-select2 bulan', 'id'=>'bulan_to'
                                ]) !!}
                            {!! $errors->first('bulan_to', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{!! $errors->has('tahun') ? 'has-error' : '' !!}">
                            {!! Form::label('tahun_to', 'Tahun II') !!}
                            {!! Form::select('tahun_to', $tahun_option, null, [
                                'class' => 'form-control js-select2 tahun', 'id'=> 'tahun_to'
                                ]) !!}
                            {!! $errors->first('tahun_to', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{!! $errors->has('type') ? 'has-error' : '' !!}">
                            {!! Form::label('type', 'Pilih Output') !!}

                        <!--     <div class="radio">
                                <label>
                                    {{ Form::radio('type', 'xls') }} Excel
                                </label>
                            </div> -->
                            <!-- <div class="radio">
                                <label>
                                    {{ Form::radio('type', 'pdf') }} PDF
                                </label>
                            </div> -->
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
    <script src="{{ asset('/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('/js/jquerynumber/jquery.number.js') }}"></script>
    <script type="text/javascript">
       var bulan =  '{{date("m") }}';
       var tahun = '{{date("Y")}}';
       $(".bulan").val(bulan);
       $(".tahun").val(tahun);
    </script>
@endsection
