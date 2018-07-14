<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form SHU Dibagi</title>
    <style>
        /* -------------------------------------------------------------- 
  
         Hartija Css Print  Framework
           * Version:   1.0 
             
        -------------------------------------------------------------- */

        body { 
        width:100% !important;
        margin:0 !important;
        padding:0 !important;
        line-height: 1.45; 
        font-family: Garamond,"Times New Roman", serif; 
        color: #000; 
        background: none; 
        font-size: 14pt; }

        /* Headings */
        h1,h2,h3,h4,h5,h6 { page-break-after:avoid; }
        h1{font-size:19pt;}
        h2{font-size:17pt;}
        h3{font-size:15pt;}
        h4,h5,h6{font-size:14pt;}


        p, h2, h3 { orphans: 3; widows: 3; }

        code { font: 12pt Courier, monospace; } 
        blockquote { margin: 1.2em; padding: 1em;  font-size: 12pt; }
        hr { background-color: #ccc; }

        /* Images */
        img { float: left; margin: 1em 1.5em 1.5em 0; max-width: 100% !important; }
        a img { border: none; }

        /* Links */
       
        abbr[title]:after { content: " (" attr(title) ")"; }

        /* Don't show linked images  */
       

        /* Table */
        table { margin: 1px; text-align:left; }
       /* th { border-bottom: 1px solid #333;  font-weight: bold; }
        td { border-bottom: 1px solid #333; }*/
        th,td { padding: 4px 10px 4px 0; }
        tfoot { font-style: italic; }
        caption { background: #fff; margin-bottom:2em; text-align:left; }
        thead {display: table-header-group;}
        img,tr {page-break-inside: avoid;} 

        /* Hide various parts from the site
        #header, #footer, #navigation, #rightSideBar, #leftSideBar 
        {display:none;}
        */
        .table-nonfluid {
            width: auto !important;
        }
        .borderless td, .borderless th {
            border: none;
        }
        
        @media print {
            a[href]:after {
              content: none !important;
            }
        }

    </style>
    <link rel="stylesheet" type="text/css" 
        href="{{asset('/admin-lte/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" type="text/css" 
    href="{{ asset('/admin-lte/bootstrap/css/bootstrap.min.css') }}">
    </head>
    <body>
        <h1 class="text-center">Form SHU Dibagi</h1>
        <br>
        <br>
        <table class="table table-nonfluid borderless">
            <tr>
                <td align="left">Periode</td>
                <td>:</td>
                <td>{{$bulan}} / {{$tahun}}</td>
            </tr>
        </table>
        <br>
        <br>
        {{ Form::open(['url'=> route('shu.save'), 'method'=>'post' ]) }}
        <input type="hidden" name="bulan" value='{{$bulan}}'>
        <input type="hidden" name="tahun" value='{{$tahun}}'>
        <table class="table table-bordered" id="main_table">
            <thead>
               <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">NIP</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Simp. Pokok</th>
                  <th class="text-center" >Simp. Wajib</th>
                  <th class="text-center" >Simp. Sukarela</th>
                  <th class="text-center" >Simpanan Total</th>
                  <th class="text-center" >Total Bunga Angs.</th>
                  <th class="text-center" >SHU Simpanan</th>
                  <th class="text-center" >SHU Bunga Angs.</th>
                  <th class="text-center" >Jumlah SHU</th>
                  <th class="text-center" >30%</th>
                  <th class="text-center" >Akumulasi SHU</th>
                  <th class="text-center" >SHU Diambil</th>
                  <th class="text-center" >SHU Tdk Diambil</th>
               </tr>
            </thead>
            <tbody>
                <?php $no = 1; 
                    $index = 0;
                    $d_shu_simpanan = 0;
                    $d_shu_angsuran = 0;
                    $d_jumlah_shu = 0;
                    $d_tigapuluh_shu = 0;
                    $d_akumulasi_shu = 0;
                ?>
                @foreach($return as $r)
                <?php 
                    $per_shu_simpanan = 0;
                    $per_shu_angsuran = 0;
                    $per_jumlah_shu = 0;
                    $per_tigapuluh_shu = 0;
                    $per_akumulasi_shu = 0;
                ?>
                <tr>
                  <td align="left">{{$no}}</td>
                  <td align="left">{{$r->nik}}</td>
                  <td align="left">{{$r->nama}}</td>
                  <td align="right">{{ number_format($r->simpanan_pokok ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->simpanan_wajib ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->simpanan_sukarela ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->jumlah_simpanan ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->total_angsuran ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->shu_simpanan ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->shu_angsuran ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->jumlah_shu ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->tigapuluh_shu ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->akumulasi_shu ,0,'.',',') }}</td>
                  <td align="right"><input type="text" id="akumulasi_shu{{$no}}" class="akumulasi_shu" name="akumulasi_shu[]" value="{{ number_format($r->akumulasi_shu ,0,'.','') }}" onkeyup="hitung({{$no}})"></td>
                  <td align="right"><input type="text" id="akumulasi_shu_t_ambil{{$no}}" class="akumulasi_shu_t_ambil" name="akumulasi_shu_t_ambil[]" value="0" onkeyup="hitung2({{$no}})" readonly></td>
                  <input type="hidden" id="shu_70_{{$no}}" name="shu_70[]" value="{{ number_format($r->akumulasi_shu ,0,'.','') }}">
                  <input type="hidden" id="id_anggota{{$no}}" name="id_anggota[]" value="{{ $r->id_anggota }}">
                  <input type="hidden" class="tigapuluh_shu" name="tigapuluh_shu[]" value="{{ number_format($r->tigapuluh_shu ,0,'.','') }}">
                </tr>
                <?php 
                    $d_shu_simpanan += $r->shu_simpanan ; 
                    $d_shu_angsuran += $r->shu_angsuran ;
                    $d_jumlah_shu += $r->jumlah_shu ;
                    $d_tigapuluh_shu += $r->tigapuluh_shu ;
                    $d_akumulasi_shu += $r->akumulasi_shu;
                ?>
                 <?php $index++; ?>
                 @if( (isset($return[$index]) && ($return[$index]->departemen != $r->departemen)) or (count($return) == $index ) )
                 <tr>
                   <td colspan="8" align="right"><b>TOTAL {{$r->departemen}}</b></td>
                   <td align="right">{{ number_format($d_shu_simpanan ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($d_shu_angsuran ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($d_jumlah_shu ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($d_tigapuluh_shu ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($d_akumulasi_shu ,0,'.',',') }}</td>
                   <td></td>
                   <td></td>
                </tr>
                <?php 
                    $d_shu_simpanan = 0;
                    $d_shu_angsuran = 0;
                    $d_jumlah_shu = 0;
                    $d_tigapuluh_shu = 0;
                    $d_akumulasi_shu = 0;
                ?>
                @endif
                <?php $no++; ?>
                @endforeach
                <tr>
                  <td colspan="14"></td>
                  <td><input class="btn btn-primary" type="submit" value="Simpan"></td>
                </tr>
            </tbody>
        </table>

         {{ Form::close() }}
        <script src="{{ asset('/admin-lte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
        <script src="{{ asset('/js/jquerynumber/jquery.number.js') }}"></script>
        <script type="text/javascript">
            $('.akumulasi_shu').number( true, 0 );
            $('.akumulasi_shu_t_ambil').number(true,0);
            $('form').on('submit', function(e) {
                 $('.akumulasi_shu').number(true, 2, '.', '');
                 $('.akumulasi_shu_t_ambil').number(true, 2, '.', '');
            });

            function hitung(baris){
              var shu_diambil = parseFloat($("#akumulasi_shu"+baris).val())||0;
              var shu_t_diambil = parseFloat($("#akumulasi_shu_t_ambil"+baris).val());
              var shu_70 = parseFloat($("#shu_70_"+baris).val());

              console.log(shu_diambil, shu_t_diambil, shu_70 );
              
              //shu_diambil = shu_70 - shu_t_diambil;
              shu_t_diambil = shu_70 - shu_diambil;

              //$("#akumulasi_shu"+baris).val(shu_diambil);
              $("#akumulasi_shu_t_ambil"+baris).val(shu_t_diambil);
            }

            function hitung2(baris){
              var shu_diambil = parseFloat($("#akumulasi_shu"+baris).val());
              var shu_t_diambil = parseFloat($("#akumulasi_shu_t_ambil"+baris).val());
              var shu_70 = parseFloat($("#shu_70_"+baris).val());

              console.log(shu_diambil, shu_t_diambil, shu_70 );
              
              shu_diambil = shu_70 - shu_t_diambil;
          
              $("#akumulasi_shu"+baris).val(shu_diambil);
            }
        </script>
    </body>
    </html>
