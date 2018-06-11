<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Perhitungan SHU</title>
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
        <h1 class="text-center">Report Perhitungan SHU</h1>
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
                    <th class="text-center" rowspan="2" style="vertical-align:middle;">No.</th>
                    <th class="text-center" rowspan="2" style="vertical-align:middle;">Bulan</th>
                    <th class="text-center" colspan="3">Penambahan Modal</th>
                    <th class="text-center" colspan="3">Akumulasi Modal</th>
                    <th class="text-center" rowspan="2" style="vertical-align:middle;">Jumlah Modal</th>
                    <th class="text-center" colspan="3">Andil Modal</th>
                    <th class="text-center" rowspan="2" style="vertical-align:middle;">Laba Rata-Rata</th>
                    <th class="text-center" colspan="3">Pembagian SHU berdasar Andil Modal</th>
                </tr>
                <tr>
                   <th class="text-center" >Anggota</th>
                   <th class="text-center" >Gamal Haryo Putro</th>
                   <th class="text-center" >Edy Sulistyanto</th>
                   <th class="text-center" >Anggota</th>
                   <th class="text-center" >Gamal Haryo Putro</th>
                   <th class="text-center" >Edy Sulistyanto</th>
                   <th class="text-center" >Anggota</th>
                   <th class="text-center" >Gamal Haryo Putro</th>
                   <th class="text-center" >Edy Sulistyanto</th>
                   <th class="text-center" >Anggota</th>
                   <th class="text-center" >Gamal Haryo Putro</th>
                   <th class="text-center" >Edy Sulistyanto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                  <td>&nbsp;</td>
                  <td>{{$prev_year}}</td>
                  <td align="right">{{number_format($modal[$prev_year]['anggota'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($modal[$prev_year]['gamal'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($modal[$prev_year]['edy'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($modal[$prev_year]['anggota'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($modal[$prev_year]['gamal'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($modal[$prev_year]['edy'] ,0,'.',',')}}</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">{{number_format($laba_total ,0,'.',',')}}</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                </tr>
                <?php 
                  $count = count($akumulasi);
                  $shu_gamal = 0;
                  $shu_edy = 0;
                  $shu_anggota = 0;

                 ?>
                @for($i=1; $i<=$count; $i++)
                <tr>
                  <td>{{$i}}</td>
                  <td>{{$akumulasi[$i]['bulan']}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['modal']['anggota'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['modal']['gamal'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['modal']['edy'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['akumulasi']['anggota'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['akumulasi']['gamal'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['akumulasi']['edy'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['jumlah_modal'] ,0,'.',',')}}</td>
                  <td align="right">{{$akumulasi[$i]['andil']['anggota']}}</td>
                  <td align="right">{{$akumulasi[$i]['andil']['gamal']}}</td>
                  <td align="right">{{$akumulasi[$i]['andil']['edy']}}</td>
                  <td align="right">{{number_format($laba_rata ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['shu']['anggota'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['shu']['gamal'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i]['shu']['edy'] ,0,'.',',')}}</td>
                </tr>
                <?php 
                    $shu_gamal += $akumulasi[$i]['shu']['gamal'];
                    $shu_edy += $akumulasi[$i]['shu']['edy'];
                    $shu_anggota += $akumulasi[$i]['shu']['anggota'];
                 ?>
                @endfor
                <tr>
                  <td>&nbsp;</td>
                  <td>Jumlah</td>
                  <td align="right">{{number_format($akumulasi[$i-1]['akumulasi']['anggota'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i-1]['akumulasi']['gamal'] ,0,'.',',')}}</td>
                  <td align="right">{{number_format($akumulasi[$i-1]['akumulasi']['edy'] ,0,'.',',')}}</td>
                  <td colspan="8">&nbsp;</td>
                  <td align="right">{{number_format($shu_anggota ,0,'.',',')}}</td>
                  <td align="right">{{number_format($shu_gamal ,0,'.',',')}}</td>
                  <td align="right">{{number_format($shu_edy ,0,'.',',')}}</td>
                </tr>
                <?php 
                if ($shu_anggota+$shu_edy+$shu_gamal > 0) {
                   $andil_anggota = $shu_anggota/($shu_anggota+$shu_edy+$shu_gamal)*100 ;
                  $andil_gamal   = $shu_gamal/($shu_anggota+$shu_edy+$shu_gamal)*100 ;
                  $andil_edy = $shu_edy/($shu_anggota+$shu_edy+$shu_gamal)*100 ;
                }else{
                  $andil_anggota = 0 ;
                  $andil_gamal   = 0 ;
                  $andil_edy = 0 ;
                }
                  

                ?>
                <tr>
                  <td colspan="13" align="center">Andil</td>
                  <td align="right">{{number_format($andil_anggota ,0,'.',',')}}</td>
                  <td align="right">{{number_format($andil_gamal ,0,'.',',')}}</td>
                  <td align="right">{{number_format($andil_edy ,0,'.',',')}}</td>
                </tr>
             
            </tbody>
        </table>
    </body>
    </html>
