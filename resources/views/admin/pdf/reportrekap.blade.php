<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Rekap</title>
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
        <h1 class="text-center">Report Rekap</h1>
        <br>
        <br>
        <table class="table table-nonfluid borderless">
            <tr>
                <td align="left">Periode</td>
                <td>:</td>
                <td>{{  date('d-m-Y',strtotime($tanggal_from)) }} s/d {{  date('d-m-Y',strtotime($tanggal_to)) }}</td>
            </tr>
        </table>
        <br>
        <br>
        <table class="table table-bordered" id="main_table">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2" style="vertical-align:middle;">Departemen</th>
                    <th class="text-center" rowspan="2" style="vertical-align:middle;">Unit Kerja</th>
                    <th class="text-center" colspan="5">Kredit</th>
                    <th class="text-center" rowspan="2" style="vertical-align:middle;">Total Kredit</th>
                    <th class="text-center" colspan="7">Debit</th>
                    <th class="text-center" rowspan="2" style="vertical-align:middle;">Total Debit</th>
                </tr>
                <tr>
                   <th class="text-center" >Pinjaman</th>
                   <th class="text-center" >Simp. Pokok</th>
                   <th class="text-center" >Simp. Wajib</th>
                   <th class="text-center" >Simp. Sukarela</th>
                   <th class="text-center" >SHU</th>
                   <th class="text-center" >Pokok Angsuran</th>
                   <th class="text-center" >Bunga</th>
                   <th class="text-center" >Simp. Pokok</th>
                   <th class="text-center" >Simp. Wajib</th>
                   <th class="text-center" >Simp. Sukarela</th>
                   <th class="text-center" >Denda</th>
                   <th class="text-center" >Pinalty</th>
                </tr>
            </thead>
            <tbody>
            <?php
                  $flag = '';
                  $gt_c_nominal_pinjaman = 0;
                  $gt_c_penarikan_pokok = 0;
                  $gt_c_penarikan_wajib = 0;
                  $gt_c_penarikan_sukarela = 0;
                  $gt_c_shu = 0;
                  $gt_c_total = 0;
                  $gt_d_pokok = 0;
                  $gt_d_bunga = 0;
                  $gt_d_simpanan_pokok = 0;
                  $gt_d_simpanan_wajib = 0;
                  $gt_d_simpanan_sukarela = 0;
                  $gt_d_denda = 0;
                  $gt_d_pinalti =0 ;
                  $gt_d_total =0;

                  $st_c_nominal_pinjaman = 0;
                  $st_c_penarikan_pokok = 0;
                  $st_c_penarikan_wajib = 0;
                  $st_c_penarikan_sukarela = 0;
                  $st_c_shu = 0;
                  $st_c_total = 0;
                  $st_d_pokok = 0;
                  $st_d_bunga = 0;
                  $st_d_simpanan_pokok = 0;
                  $st_d_simpanan_wajib = 0;
                  $st_d_simpanan_sukarela = 0;
                  $st_d_denda = 0;
                  $st_d_pinalti =0 ;
                  $st_d_total =0;

                  $i = 0;
            ?>
                  @foreach ($rekap as $r)
                  <tr>
                    <td align="left">{{$r->departemen}}</td>
                    <td align="left"><a href="{{ action('Report\ReportRekapController@unit') }}?unit_id={{ $r->unit_id }}&from={{$tanggal_from}}&to={{$tanggal_to}}" target="_blank">{{ $r->unit }}</a></td>
                    <td align="right">{{number_format($r->c_nominal_pinjaman,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->c_penarikan_pokok,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->c_penarikan_wajib,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->c_penarikan_sukarela,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->c_shu,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->c_total,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->d_pokok,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->d_bunga,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->d_simpanan_pokok,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->d_simpanan_wajib,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->d_simpanan_sukarela,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->d_denda,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->d_pinalti,0,'.',',')}}</td>
                    <td align="right">{{number_format($r->d_total,0,'.',',')}}</td>
                  </tr>
                  <?php
                    $gt_c_nominal_pinjaman += $r->c_nominal_pinjaman;
                    $gt_c_penarikan_pokok += $r->c_penarikan_pokok;
                    $gt_c_penarikan_wajib += $r->c_penarikan_wajib;
                    $gt_c_penarikan_sukarela += $r->c_penarikan_sukarela;
                    $gt_c_shu += $r->c_shu;
                    $gt_c_total += $r->c_total;
                    $gt_d_pokok += $r->d_pokok;
                    $gt_d_bunga += $r->d_bunga;
                    $gt_d_simpanan_pokok += $r->d_simpanan_pokok;
                    $gt_d_simpanan_wajib += $r->d_simpanan_wajib;
                    $gt_d_simpanan_sukarela += $r->d_simpanan_sukarela;
                    $gt_d_denda += $r->d_denda;
                    $gt_d_pinalti += $r->d_pinalti  ;
                    $gt_d_total += $r->d_total;

                    $st_c_nominal_pinjaman += $r->c_nominal_pinjaman;
                    $st_c_penarikan_pokok += $r->c_penarikan_pokok;
                    $st_c_penarikan_wajib += $r->c_penarikan_wajib;
                    $st_c_penarikan_sukarela += $r->c_penarikan_sukarela;
                    $st_c_shu += $r->c_shu;
                    $st_c_total += $r->c_total;
                    $st_d_pokok += $r->d_pokok;
                    $st_d_bunga += $r->d_bunga;
                    $st_d_simpanan_pokok += $r->d_simpanan_pokok;
                    $st_d_simpanan_wajib += $r->d_simpanan_wajib;
                    $st_d_simpanan_sukarela += $r->d_simpanan_sukarela;
                    $st_d_denda += $r->d_denda;
                    $st_d_pinalti += $r->d_pinalti  ;
                    $st_d_total += $r->d_total;

                    $i++;
                  ?>
                  @if( (isset($rekap[$i]) && ($rekap[$i]->departemen != $r->departemen)) or (count($rekap) == $i ) )
                    <tr>
                      <td>&nbsp;</td>
                      <td><b>Subtotal {{ $r->departemen }} </b></td>
                      <td align="right"><b>{{number_format($st_c_nominal_pinjaman,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_c_penarikan_pokok,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_c_penarikan_wajib,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_c_penarikan_sukarela,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_c_shu,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_c_total,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_d_pokok,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_d_bunga,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_d_simpanan_pokok,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_d_simpanan_wajib,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_d_simpanan_sukarela,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_d_denda,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_d_pinalti,0,'.',',')}}</b></td>
                      <td align="right"><b>{{number_format($st_d_total,0,'.',',')}}</b></td>
                    </tr>

                    <?php
                      $st_c_nominal_pinjaman = 0;
                      $st_c_penarikan_pokok = 0;
                      $st_c_penarikan_wajib = 0;
                      $st_c_penarikan_sukarela = 0;
                      $st_c_shu = 0;
                      $st_c_total = 0;
                      $st_d_pokok = 0;
                      $st_d_bunga = 0;
                      $st_d_simpanan_pokok = 0;
                      $st_d_simpanan_wajib = 0;
                      $st_d_simpanan_sukarela = 0;
                      $st_d_denda = 0;
                      $st_d_pinalti =0 ;
                      $st_d_total =0;
                    ?>
                  @endif
                  @endforeach
                  <tr>
                    <td>&nbsp;</td>
                    <td><b>Grand Total</b></td>
                    <td align="right"><b>{{number_format($gt_c_nominal_pinjaman,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_c_penarikan_pokok,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_c_penarikan_wajib,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_c_penarikan_sukarela,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_c_shu,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_c_total,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_d_pokok,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_d_bunga,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_d_simpanan_pokok,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_d_simpanan_wajib,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_d_simpanan_sukarela,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_d_denda,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_d_pinalti,0,'.',',')}}</b></td>
                    <td align="right"><b>{{number_format($gt_d_total,0,'.',',')}}</b></td>
                  </tr>
            </tbody>
        </table>
    </body>
    </html>
