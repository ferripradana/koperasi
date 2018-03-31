@extends('layouts.app')

@section('dashboard')
   Report Rekap Per Bulan
   <small>Report Rekap Per Bulan</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Report Rekap</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Report Rekap</h3>
              </div>
                <!-- /.box-header -->
                {!! Form::open(['url' => route('reportbulanrekap.post'), 'method' => 'post', "target"=>"_blank" ]) !!}
                    <div class="box-body">
                        <div class="form-group has-feedback{!! $errors->has('bulan') ? 'has-error' : '' !!}">
                            {!! Form::label('bulan', 'Bulan') !!}
                            {!! Form::select('bulan', $bulan_option, null, [
                                'class' => 'form-control js-select2', 'id'=>'bulan'
                                ]) !!}
                            {!! $errors->first('tahun', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{!! $errors->has('tahun') ? 'has-error' : '' !!}">
                            {!! Form::label('tahun', 'Tahun') !!}
                            {!! Form::select('tahun', $tahun_option, null, [
                                'class' => 'form-control js-select2', 'id'=> 'tahun'
                                ]) !!}
                            {!! $errors->first('tahun', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{!! $errors->has('type') ? 'has-error' : '' !!}">
                            {!! Form::label('type', 'Pilih Output') !!}

                        <!--     <div class="radio">
                                <label>
                                    {{ Form::radio('type', 'xls') }} Excel
                                </label>
                            </div> -->
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
    <script src="{{ asset('/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('/js/jquerynumber/jquery.number.js') }}"></script>
    <script type="text/javascript">
       var bulan =  '{{date("m") }}';
       var tahun = '{{date("Y")}}';
       $("#bulan").val(bulan);
       $("#tahun").val(tahun);
    </script>
@endsection
