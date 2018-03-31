<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proyeksi Angsuran</title>
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
        <h1 class="text-center">Proyeksi Angsuran Pinjaman</h1>
        <br>
        <br>
        <table class="table table-nonfluid borderless">
            <tr>
                <td align="left">Tanggal Pengajuan</td>
                <td>:</td>
                <td>{{  $proyeksi[0]->peminjaman->tanggal_pengajuan }}</td>
            </tr>
             <tr>
                <td align="left">No. Transaksi Peminjaman</td>
                <td>:</td>
                <td>{{  $proyeksi[0]->peminjaman->no_transaksi }}</td>
            </tr>
            <tr>
                 <td align="left">NIK</td>
                <td>:</td>
                <td>{{  $proyeksi[0]->peminjaman->anggota->nik }}</td>
            </tr>
            <tr>
                 <td align="left">Nama</td>
                <td>:</td>
                <td>{{  $proyeksi[0]->peminjaman->anggota->nama }}</td>
            </tr>
            <tr>
                 <td align="left">Alamat</td>
                <td>:</td>
                <td>{{  $proyeksi[0]->peminjaman->anggota->alamat }}</td>
            </tr>
            <tr>
                 <td align="left">Nominal Pinjaman</td>
                <td>:</td>
                <td>{{  number_format($proyeksi[0]->peminjaman->nominal,0,'.',',') }}</td>
            </tr>
            <tr>
                 <td align="left">Bunga</td>
                <td>:</td>
                <td>{{  $proyeksi[0]->peminjaman->bunga_persen }} %  / {{  number_format($proyeksi[0]->peminjaman->bunga_nominal,0,'.',',') }} </td>
            </tr>
             <tr>
                 <td align="left">Plafon</td>
                <td>:</td>
                <td>{{  $proyeksi[0]->peminjaman->anggota->jabatane->plafone }}</td>
            </tr>
        </table>
        <br>
        <br>
        <table class="table table-bordered" id="main_table">
            <thead>
                <tr>
                    <th class="text-center">Bulan Ke</th>
                    <th class="text-center">Tanggal<br>Angsuran</th>
                    <th class="text-center">Pokok Pinjaman</th>
                    <th class="text-center">Pokok Angsuran</th>
                    <th class="text-center">Bunga<br>(Nominal)</th>
                    <th class="text-center">Angsuran Per Bulan</th>
                    <th class="text-center">Pokok <br> Pinjaman</th>
                    <th class="text-center">Status <br> &nbsp;</th>
                </tr>
            </thead>
            <tbody>
                  <?php
                    $i = 0;
                    $total = 0;

                    $t_cicilan = 0;
                    $t_bunga = 0;
                    $t_angbulan = 0;
                  ?>   
                  @foreach ($proyeksi as $p)
                  <?php
                     if ($i==0) {
                        $total = $p->peminjaman->nominal;
                    }
                  ?>
                  <tr>
                      <td align="center">{{ $p->angsuran_ke }}</td>
                      <td align="center">{{ $p->tgl_proyeksi}}</td>
                      <td align="right">{{number_format($total, 0, ".", ",")}}</td>
                      <td align="right">{{number_format($p->cicilan,0,'.',',' )}}</td>
                      <td align="right">{{number_format($p->bunga_nominal, 0, '.',',') }}</td>
                      <td align="right">{{number_format($p->cicilan + $p->bunga_nominal, 0, '.',',')}}</td>
                      <td align="right">{{number_format(($total-($p->cicilan)),0, '.', ',')}}</td>
                      <td align="right">{{ ($p->status == 1) ? "Lunas" : "Belum" }}</td>
                  </tr>
                  <?php
                     $total -= $p->cicilan ;
                     $t_cicilan += $p->cicilan;
                     $t_bunga += $p->bunga_nominal;
                     $t_angbulan += $p->cicilan + $p->bunga_nominal;
                     $i++;
                  ?>
                  @endforeach
                   <tr>
                      <td align="right" colspan="3"><strong>TOTAL</strong></td>
                      <td align="right">{{number_format($t_cicilan,0,'.',',') }}</td>
                      <td align="right">{{number_format($t_bunga,0,'.',',' )}}</td>
                      <td align="right">{{number_format($t_angbulan,0,'.',',' ) }}</td>
                      <td align="center" colspan="2">&nbsp;</td>
                  </tr>
            </tbody>
        </table>
    </body>
    </html>
