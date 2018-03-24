@extends('layouts.app')

@section('dashboard')
    View Pinjaman
    <small>View Pinjaman</small>
@endsection

@section('breadcrumb')
<li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="#"><i class="fa fa-book"></i> Peminjaman</a></li>
   <li><a href="{{ url('/admin/loan/peminjaman') }}">Peminjaman</a></li>
   <li class="active">View Peminjaman</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">View Proyeksi Angsuran</h3>
                        <div class="box-body">
                            <div class="form-group col-md-6">
                                {{ Form::label('id_peminjaman', 'No Transaksi') }}
                                {!! Form::select('id_peminjaman', $no_transaksi = [''=>'-- Pilih Peminjaman --'] + App\Model\Peminjaman::where('status',1)->pluck('no_transaksi','id')->all(), null, ['class' => 'form-control js-select2', 'required'=>'required', 'id'=>'id_peminjaman']) !!}
                                {!! $errors->first('id_peminjaman','<p class="help-block">:message</p>') !!}
                             </div>
                             <div id="tabele" class="col-md-12" >
                           
                             </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript">
        var url = "{{ route('peminjaman.viewproyeksi') }}";
         $('#id_peminjaman').change(function() {
             $("#tabele").html('');
             var html = '<h2>Daftar Proyeksi Angsuran</h2>'+
                        '<table style="" class="table table-striped table-hover">'+
                        '<thead><tr>' +
                            '<th>No</th><th>Nama</th><th>No. Transaksi</th><th>Tanggal Angsur</th><th>Pokok</th><th>Bunga</th><th>Status</th>' +
                            '</tr></thead><tbody>'  ; 
             $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                data: 'id_peminjaman=' + this.value ,
                beforeSend: function() {
                    $("#loader").show();
                },
                success: function(data) {
                     var no = 1;
                     var cicilan_total = 0;
                     var bunga_total = 0;

                     for (var i = 0; i < data.length; i++) {
                        html += '<tr><td>'+ no++ +'</td><td>'+ data[i].peminjaman.anggota.nama +'</td><td>'+data[i].peminjaman.no_transaksi+'</td><td>'+data[i].tgl_proyeksi+'</td><td>'+data[i].cicilanview+'</td><td>'+data[i].bunganominalview+'</td><td>'+data[i].statusview+'</td></tr>';
                        cicilan_total += parseFloat(data[i].cicilan);
                        bunga_total += parseFloat(data[i].bunga_nominal);
                    }

                     html += '<tr><td colspan="4"><b>TOTAL</b><td><b>'+cicilan_total.formatMoney(2, ',', '.')+'</b></td><td><b>'+bunga_total.formatMoney(2, ',', '.')+'</b></td><td>&nbsp;</td></tr>';

                    html += '</tbody><table>';
                    $("#tabele").html(html);
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

    </script>

    <!-- Image loader -->
    <div id='loader' style='display: none;'>
      <center><img src="{{ URL::to('/img/200_d.gif') }}"  width='100px' height='100px'></center>
    </div>
    <!-- Image loader -->
@endsection

