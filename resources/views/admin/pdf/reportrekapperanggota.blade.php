<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Rekap Per Anggota</title>
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
        a:link, a:visited { background: transparent; font-weight: 700; text-decoration: underline;color:#333; }
        a:link[href^="http://"]:after, a[href^="http://"]:visited:after { content: " (" attr(href) ") "; font-size: 90%; }

        abbr[title]:after { content: " (" attr(title) ")"; }

        /* Don't show linked images  */
        a[href^="http://"] {color:#000; }
        a[href$=".jpg"]:after, a[href$=".jpeg"]:after, a[href$=".gif"]:after, a[href$=".png"]:after { content: " (" attr(href) ") "; display:none; }

        /* Don't show links that are fragment identifiers, or use the `javascript:` pseudo protocol .. taken from html5boilerplate */
        a[href^="#"]:after, a[href^="javascript:"]:after {content: "";}

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
    </style>
    <link rel="stylesheet" type="text/css" 
        href="{{asset('/admin-lte/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" type="text/css" 
    href="{{ asset('/admin-lte/bootstrap/css/bootstrap.min.css') }}">
    </head>
    <body>
        <h1 class="text-center">Report Rekap Per Anggota</h1>
        <br>
        <br>
        <table class="table table-nonfluid borderless">
            <tr>
              <td>Unit Kerja</td>
              <td>:</td>
              <td>{{$anggota->unit->name}}</td>
            </tr>
            <tr>
              <td>NIP</td>
              <td>:</td>
              <td>{{$anggota->nik}}</td>
            </tr>
            <tr>
              <td>Nama</td>
              <td>:</td>
              <td>{{$anggota->nama}}</td>
            </tr>
            <tr>
              <td>Level</td>
              <td>:</td>
              <td>{{$anggota->jabatane->nama_jabatan}}</td>
            </tr>
        </table>
        <br>
        <br>
        <table class="table table-bordered" id="main_table">
            <thead>
                <tr>
                    <th class="text-center">Bulan Ke</th>
                    <th class="text-center">Bulan</th>
                    <th class="text-center">Pokok Pinjaman</th>
                    <th class="text-center">Pokok Angsuran</th>
                    <th class="text-center">Bunga</th>
                    <th class="text-center">Denda</th>
                    <th class="text-center">Saldo Pinjaman</th>
                </tr>
            </thead>
            <tbody>
                  <?php
                    $saldo_awal = 0;
                    $gt_pokok = 0;
                    $gt_bunga =0;
                    $gt_denda = 0;
                    $i = 0;

                  ?>
                     
                  @foreach ($rekap as $p)

                  <?php 
                    if($i==0){
                      $saldo_awal = $p->nominal;
                    }
                  ?>
                  <tr>
                      <td align="center">{{ $p->angsuran_ke }}</td>
                      <td align="center">{{ date('d-m-Y', strtotime($p->tanggal_transaksi)) }}</td>
                      <td align="right">{{number_format($saldo_awal, 0, ".", ",")}}</td>
                      <td align="right">{{number_format($p->pokok,0,'.',',' )}}</td>
                      <td align="right">{{number_format($p->bunga, 0, '.',',') }}</td>
                      <td align="right">{{number_format($p->denda, 0, '.',',')}}</td>
                  <?php
                    $saldo_awal -= $p->pokok;
                  ?>
                      <td align="right">{{number_format($saldo_awal,0, '.', ',')}}</td>
                  </tr>
                  <?php
                    $gt_pokok += $p->pokok;
                    $gt_bunga += $p->bunga;
                    $gt_denda += $p->denda;
                    $i++;
                  ?>
                  @endforeach
                   <tr>
                      <td align="right" colspan="3">TOTAL</td>
                      <td align="right">{{number_format($gt_pokok,0,'.',',' )}}</td>
                      <td align="right">{{number_format($gt_bunga, 0, '.',',') }}</td>
                      <td align="right">{{number_format($gt_denda, 0, '.',',')}}</td>
                      <td align="right"></td>
                  </tr>
            </tbody>
        </table>
    </body>
    </html>
