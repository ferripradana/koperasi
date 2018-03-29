@extends('layouts.app')

@section('dashboard')
   Report Rekap
   <small>Report Proyeksi Angsuran Pinjaman</small>
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
                {!! Form::open(['url' => route('reportrekap.post'), 'method' => 'post', "target"=>"_blank" ]) !!}
                    <div class="box-body">
                        <div class="form-group has-feedback{!! $errors->has('tanggal_from') ? 'has-error' : '' !!}">
                                {{ Form::label('tanggal_from', 'Dari') }}
                                {{ Form::text('tanggal_from', null, ['class'=>'form-control date', 'placeholder'=> 'Dari', 'required'=>'required', 'readonly'=>'readonly', 'id' => 'tanggal_from']) }}    
                        </div>

                        <div class="form-group has-feedback{!! $errors->has('tanggal_to') ? 'has-error' : '' !!}">
                                {{ Form::label('tanggal_to', 'Sampai') }}
                                {{ Form::text('tanggal_to', null, ['class'=>'form-control date', 'placeholder'=> 'Sampai', 'required'=>'required', 'readonly'=>'readonly', 'id' => 'tanggal_to']) }}
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
       
        var from = "{{ date('d-m-Y', strtotime('-7 days')) }}";
        $("#tanggal_from").val(from);

        var to = "{{ date('d-m-Y') }}";
        $("#tanggal_to").val(to);

        $('.date').datepicker({  
            format: 'dd-mm-yyyy',
            todayHighlight: true
        }); 

    </script>
@endsection
