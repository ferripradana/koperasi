@extends('layouts.app')

@section('dashboard')
   Report Entry Per COA Unit
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Report Entry Per COA</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Report Entry Per COA</h3>
              </div>
                <!-- /.box-header -->
                {!! Form::open(['url' => route('entrypercoaunit.post'), 'method' => 'post', "target"=>"_blank" ]) !!}
                    <div class="box-body">
                        <div class="form-group has-feedback{{$errors->has('coa') ? ' has-error' : '' }}">
                            {{ Form::label('coa', 'COA') }}
                            {!! Form::select('coa', $coa , null, ['class' => 'form-control js-select2']) !!}
                            {!! $errors->first('coa','<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{{$errors->has('tanggal_from') ? ' has-error' : '' }}">
                            {{ Form::label('tanggal_from', 'Dari') }}
                            {{ Form::text('tanggal_from', null, ['class'=>'form-control date', 'placeholder'=> 'Dari', 'required'=>'required', 'readonly'=>'readonly', 'id'=> 'tanggal_from' ]) }}
                            {!! $errors->first('tanggal_from','<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{{$errors->has('tanggal_to') ? ' has-error' : '' }}">
                            {{ Form::label('tanggal_to', 'Sampai') }}
                            {{ Form::text('tanggal_to', null, ['class'=>'form-control date', 'placeholder'=> 'Sampai', 'required'=>'required', 'readonly'=>'readonly', 'id'=> 'tanggal_to' ]) }}
                            {!! $errors->first('tanggal_to','<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{!! $errors->has('id_unit') ? 'has-error' : '' !!}">
                            {!! Form::label('id_unit', 'Tahun Awal') !!}
                            {!! Form::select('id_unit', $unit = App\Model\Unit::pluck('name','id')->all(), null, ['class' => 'form-control js-select2']) !!}
                            {!! $errors->first('id_unit', '<p class="help-block">:message</p>') !!}
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

       $('.date').datepicker({  
           format: 'dd-mm-yyyy',
           todayHighlight: true
         });  

        $("#tanggal_from").datepicker('setDate', 'today');
        $("#tanggal_to").datepicker('setDate', 'today');
    </script>
@endsection
