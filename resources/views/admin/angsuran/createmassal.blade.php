@extends('layouts.app')

@section('dashboard')
    Input Angsuran Massal
    <small>Input Angsuran Massal</small>
@endsection

@section('breadcrumb')
<li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="#"><i class="fa fa-book"></i> Peminjaman</a></li>
   <li><a href="{{ url('/admin/loan/angsuran') }}">Angsuran</a></li>
   <li class="active">Input Angsuran Massal</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Input Angsuran Massal</h3>
                        <div class="box-body">
                            <div class="form-group col-md-6">
                                {{ Form::label('id_unit', 'Unit Kerja Karyawan') }}
                                {!! Form::select('id_unit', $unit = ['0'=>'-- Pilih Unit Kerja --'] + App\Model\Unit::pluck('name','id')->all(), null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=>'id_unit']) !!}
                                {!! $errors->first('id_unit','<p class="help-block">:message</p>') !!}
                             </div>
                             <div class="form-group col-md-3">
                                {{ Form::label('tanggal_from', 'Dari') }}
                                {{ Form::text('tanggal_from', null, ['class'=>'form-control date', 'placeholder'=> 'Dari', 'required'=>'required', 'readonly'=>'readonly', 'id' => 'tanggal_from']) }}
                             </div>
                              <div class="form-group col-md-3">
                                {{ Form::label('tanggal_to', 'Sampai') }}
                                {{ Form::text('tanggal_to', null, ['class'=>'form-control date', 'placeholder'=> 'Sampai', 'required'=>'required', 'readonly'=>'readonly', 'id' => 'tanggal_to']) }}
                             </div>
                             {{ Form::open(['url'=> route('angsuran.storemasal'), 'method'=>'post' ]) }}
                             <div id="tabele" class="col-md-12" >        
                             </div>
                             {{ Form::close() }}
                        </div>
                </div>
            </div>
        </div>
    </div>
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


        var url = "{{ route('angsuran.viewjatuhtempo') }}";
         $('#id_unit').change(function() {
             $("#tabele").html('');
              var html = '<table style="" class="table table-striped table-hover">'+
                        '<thead><tr>' +
                            '<td>No</td><td>No. Transaksi</td><td>Anggota</td><td>Jatuh Tempo</td><td>Nominal</td><td>Saldo</td><td>Angsuran Ke</td><td>Pokok Bulanan</td><td>Bunga Bulanan</td><td>Simpanan Wajib</td><td>Denda</td><td>Total</td><td>' +
                            '</tr></thead><tbody>'  ;         
             $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                data: 'id_unit=' + this.value + '&from='+ $("#tanggal_from").val() +'&to= '+ $("#tanggal_to").val(), 
                beforeSend: function() {
                    $("#loader").show();
                },
                success: function(data) {
                    $no = 1;
                    for (var i = 0; i < data.length; i++) {
                        html += '<tr id="baris_'+i+'">'+
                                    '<td>'+ $no++ +'</td>'+
                                    '<td><select style="width:100px" name="id_pinjaman[]" id="id_pinjaman_'+i+'" class="form-control"><option value="'+data[i].id+'">'+data[i].no_transaksi+'</option></select></td>'+
                                    '<td><select style="width:140px" name="id_anggota[]" id="id_anggota_'+i+'" class="form-control"><option value="'+data[i].id_anggota+'">'+data[i].nama_lengkap+'</option></select></td>'+
                                    '<td><select style="width:100px" name="id_proyeksi[]" id="id_proyeksi_'+i+'" class="form-control"><option value="'+data[i].id_proyeksi+'">'+data[i].tgl_proyeksi+'</option></select></td>'+
                                    '<td><input readonly="readonly" class="form-control nominalpinjaman" id="nominalpinjaman_'+i+'" type="text" name="nominalpinjaman[]" value="'+data[i].nominal+'"></td>'+
                                    '<td><input readonly="readonly" class="form-control saldopinjaman" id="saldopinjaman_'+i+'" type="text" name="saldopinjaman[]" value="'+data[i].saldopinjaman+'"></td>'+
                                    '<td><input readonly="readonly" class="form-control" id="angsuran_ke_'+i+'" type="text" name="angsuran_ke[]" value="'+data[i].angsuran_ke+'"></td>'+
                                    '<td><input readonly="readonly" class="form-control pokok" id="pokok_'+i+'" type="text" name="pokok[]" value="'+data[i].cicilan+'"></td>'+
                                    '<td><input readonly="readonly" class="form-control bunga" id="bunga_'+i+'" type="text" name="bunga[]" value="'+data[i].bunga_nominal+'"></td>'+
                                    '<td><input class="form-control simpanan_wajib" id="simpanan_wajib_'+i+'" type="text" name="simpanan_wajib[]" value="'+data[i].simpanan_wajib+'" onkeyup="hitung('+i+')" ></td>'+
                                    '<td><input class="form-control denda" onkeyup="hitung('+i+')" id="denda_'+i+'" type="text" name="denda[]" value="'+data[i].denda+'"></td>'+
                                    '<td><input readonly="readonly" class="form-control total" id="total_'+i+'" type="text" name="total[]" value="'+data[i].total+'"></td>'+
                                    '<td><a id="minus_'+i+'" onclick="deletebaris('+i+')" class="btn btn-primary">-</a></td>'+
                                '</tr>';
                    }
                    if ($no>1) {
                         html += '<tr><td colspan = "13" align="right"><input class="btn btn-primary" type="submit" value="Simpan"></td></tr></tbody><table>';     
                    }

                    $("#tabele").html(html);
                    $(".pokok").number(true, 0);
                    $('.bunga').number( true, 0 );
                    $('.simpanan_wajib').number( true, 0 );
                    $(".denda").number(true, 0);
                    $(".total").number(true, 0);
                    $(".nominalpinjaman").number(true, 0);
                    $('.saldopinjaman').number(true, 0);
                    $("#loader").hide();
                }
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


         });

         function hitung(baris){
            var pokok = parseFloat($("#pokok_"+baris).val());
            var bunga =parseFloat($("#bunga_"+baris).val());
            var simpanan_wajib =  parseFloat($("#simpanan_wajib_"+baris).val());
            var denda = parseFloat($("#denda_"+baris).val());
            var total = pokok + bunga + simpanan_wajib + denda;
            console.log(total);
            $("#total_"+baris).val(total);
         }


         $('form').on('submit', function(e) {
            $('.pokok').number(true, 2, '.', '');
            $('.bunga').number(true, 2, '.', '');
            $('.simpanan_wajib').number(true, 2, '.', '');
            $('.denda').number(true, 2, '.', '');
            $('.total').number(true, 2, '.', '');
            $('.nominalpinjaman').number(true, 2, '.', '');
            $('.saldopinjaman').number(true, 2, '.', '');
        });

         function deletebaris (baris) {
            $('#baris_'+baris).remove();
         }
         


    </script>

    <!-- Image loader -->
    <div id='loader' style='display: none;'>
      <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='100px' height='100px'></center>
    </div>
    <!-- Image loader -->
@endsection

