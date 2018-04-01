<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Saldo Per Anggota</title>
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
        <h1 class="text-center">Report Saldo Per anggota</h1>
        <br>
        <br>
        <table class="table table-bordered" id="main_table">
            <thead>
                <tr>
                    <th class="text-center" >Departemen</th>
                    <th class="text-center" >Unit Kerja</th>
                    <th class="text-center">NIP</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Level</th>
                    <th class="text-center">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
            <?php
                  $gt_saldo =  0;
                  $sub_saldo = 0;
                  $i = 0;
            ?>
                  @foreach ($saldo as $r)
                  <tr>
                    <td align="left">{{$r->departemen}}</td>
                    <td align="left">{{$r->unit}}</td>
                    <td align="left">{{$r->nik}}</td>
                    <td align="left">{{$r->nama}}</td>
                    <td align="left">{{$r->nama_jabatan}}</td>
                    <td align="right">{{number_format($r->saldo,0,'.',',')}}</td>
                  </tr>
                  <?php
                    $gt_saldo +=  $r->saldo;
                    $sub_saldo += $r->saldo;
                    $i++;
                  ?>
                  @if( (isset($saldo[$i]) && ($saldo[$i]->unit != $r->unit)) or (count($saldo) == $i ) )
                    <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left">TOTAL {{$r->unit}}</td>
                      <td align="left" colspan="3">&nbsp;</td>
                      <td align="right">{{number_format($sub_saldo,0,'.',',')}}</td>
                    </tr>
                    <?php
                       $sub_saldo = 0;
                    ?>
                  @endif
                  @endforeach
                  <tr>
                    <td colspan="5"><b>Grand Total</b></td>
                    <td align="right"><b>{{number_format($gt_saldo,0,'.',',')}}</b></td>  
                  </tr>
            </tbody>
        </table>
    </body>
    </html>
