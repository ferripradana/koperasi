@extends('layouts.app')

@section('dashboard')
    Simpanan
    <small>Transaksi Simpanan</small>
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Income</a></li>
    <li><a href="{{url('/admin/transaction/simpanan')}}">Simpanan</a></li>
    <li class="active">View Simpanan Anggota</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">View Simpanan Anggota</h3>
                        <div class="box-body">
                            <div class="form-group col-md-6 has-feedback{{$errors->has('id_anggota') ? ' has-error' : '' }}">
                                {{ Form::label('id_anggota', 'Nama Anggota') }}
                                {!! Form::select('id_anggota', $anggota = [''=>'-- Pilih Anggota --'] + App\Model\Anggota::pluck('nama','id')->all(), null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=>'id_anggota']) !!}
                                {!! $errors->first('id_anggota','<p class="help-block">:message</p>') !!}
                             </div>
                             <div id="tabele" class="col-md-12" >
                             </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var url = "{{ route('simpanan.viewtabungan') }}";
        
        $('#id_anggota').change(function() {
             $("#tabele").html('');
            var html = '<table style="color:blue" class="table table-striped">'+
                        '<thead><tr>' +
                            '<td>No</td><td>No. Transaksi</td><td>Tanggal</td><td>Jenis Simpanan</td><td>Nominal</td>' +
                            '</tr></thead><tbody>'  ;           
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                data: 'id_anggota=' + this.value ,
                success: function(data) {
                    var gt = 0;
                    var flag = '';
                    var no = 1;
                    for (var i = 0; i < data.length; i++) {
                        if (i>0 && data[i].id_simpanan != flag ) {
                            html += '<tr><td colspan="4"><strong>Total</strong></td><td align="right"><strong>'+gt.formatMoney(2, ',', '.')+'</strong></td></tr>';
                            gt = 0;
                            no = 1;
                        }

                        html += '<tr><td>'+ no++ +'</td><td>'+data[i].no_transaksi+'</td><td>'+data[i].tanggal_transaksi+'</td><td>'+data[i].jenissimpanan.nama_simpanan+'</td><td align="right">'+data[i].nominalview+'</td></tr>';
                        
                        gt +=  parseFloat(data[i].nominal);
                        flag = data[i].id_simpanan;    
                    }
                    html += '<tr><td colspan="4"><strong>Total</strong></td><td align="right"><strong>'+gt.formatMoney(2, ',', '.')+'</strong></td></tr>';

                     html += '</tbody><table>';
                     $("#tabele").html(html);
                }
            });


           
        });

        Number.prototype.formatMoney = function(c, d, t){
        var n = this, 
            c = isNaN(c = Math.abs(c)) ? 2 : c, 
            d = d == undefined ? "." : d, 
            t = t == undefined ? "," : t, 
            s = n < 0 ? "-" : "", 
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
            j = (j = i.length) > 3 ? j % 3 : 0;
           return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
         };


    </script>
@endsection

