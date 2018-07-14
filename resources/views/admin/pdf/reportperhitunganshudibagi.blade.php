<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Perhitungan SHU Dibagi</title>
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
        <h1 class="text-center">Report Perhitungan SHU Dibagi</h1>
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
        <table class="table table-bordered" id="main_table">
            <thead>
               <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">NIP</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Bulan</th>
                  <th class="text-center">Simp. Pokok</th>
                  <th class="text-center" >Simp. Wajib</th>
                  <th class="text-center" >Simp. Sukarela</th>
                  <th class="text-center" >Simpanan Total</th>
                  <th class="text-center" >Total Bunga Angs.</th>
                  <th class="text-center" >SHU Simpanan</th>
                  <th class="text-center" >SHU Bunga Angs.</th>
                  <th class="text-center" >Jumlah SHU</th>
                  <th class="text-center" >30% Diambil</th>
                  <th class="text-center" >70% Disimpan</th>
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
                @for($i=1;$i<=$bulan;$i++)
                <tr>
                  <td align="left">@if($i==1){{$no}} @endif</td>
                  <td align="left">@if($i==1){{$r->nik}} @endif</td>
                  <td align="left">@if($i==1){{$r->nama}} @endif</td>
                  <td align="left">{{ $bulan_option[$i]}}</td>
                  <td align="right">{{ number_format($r->simpanan_pokok[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->simpanan_wajib[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->simpanan_sukarela[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->jumlah_simpanan[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->total_angsuran[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->shu_simpanan[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->shu_angsuran[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->jumlah_shu[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->tigapuluh_shu[$i] ,0,'.',',') }}</td>
                  <td align="right">{{ number_format($r->akumulasi_shu[$i] ,0,'.',',') }}</td>
                </tr>
                <?php 
                    $per_shu_simpanan += $r->shu_simpanan[$i] ;
                    $per_shu_angsuran += $r->shu_angsuran[$i] ;
                    $per_jumlah_shu += $r->jumlah_shu[$i] ;
                    $per_tigapuluh_shu += $r->tigapuluh_shu[$i] ;
                    $per_akumulasi_shu += $r->akumulasi_shu[$i];

                    $d_shu_simpanan += $r->shu_simpanan[$i] ;
                    $d_shu_angsuran += $r->shu_angsuran[$i] ;
                    $d_jumlah_shu += $r->jumlah_shu[$i] ;
                    $d_tigapuluh_shu += $r->tigapuluh_shu[$i] ;
                    $d_akumulasi_shu += $r->akumulasi_shu[$i];
                ?>
                @endfor
                <?php $index++; ?>
                <tr>
                   <td colspan="9" align="right"><b>TOTAL</b></td>
                   <td align="right">{{ number_format($per_shu_simpanan ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($per_shu_angsuran ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($per_jumlah_shu ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($per_tigapuluh_shu ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($per_akumulasi_shu ,0,'.',',') }}</td>
                </tr>
                 @if( (isset($return[$index]) && ($return[$index]->departemen != $r->departemen)) or (count($return) == $index ) )
                 <tr>
                   <td colspan="9" align="right"><b>TOTAL {{$r->departemen}}</b></td>
                   <td align="right">{{ number_format($d_shu_simpanan ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($d_shu_angsuran ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($d_jumlah_shu ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($d_tigapuluh_shu ,0,'.',',') }}</td>
                   <td align="right">{{ number_format($d_akumulasi_shu ,0,'.',',') }}</td>
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
            </tbody>
        </table>
    </body>
    </html>
