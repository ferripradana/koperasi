<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Entry Per COA</title>
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
       /* a:link, a:visited { background: transparent; font-weight: 700; text-decoration: underline;color:#333; }
        a:link[href^="http://"]:after, a[href^="http://"]:visited:after { content: " (" attr(href) ") "; font-size: 90%; }*/

        abbr[title]:after { content: " (" attr(title) ")"; }

        /* Don't show linked images  */
       /* a[href^="http://"] {color:#000; }
        a[href$=".jpg"]:after, a[href$=".jpeg"]:after, a[href$=".gif"]:after, a[href$=".png"]:after { content: " (" attr(href) ") "; display:none; }
*/
        /* Don't show links that are fragment identifiers, or use the `javascript:` pseudo protocol .. taken from html5boilerplate */
       /* a[href^="#"]:after, a[href^="javascript:"]:after {content: "";}*/

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
        <h1 class="text-center">Report Entry Per COA</h1>
        <br>
        <br>
        <table class="table table-nonfluid borderless">
            <tr>
                <td align="left">Periode</td>
                <td>:</td>
                <td>{{$from}} / {{$to}}</td>
            </tr>
             <tr>
                <td align="left">COA</td>
                <td>:</td>
                <td>{{ $coa->code }} - {{ $coa->nama }}</td>
            </tr>
            <tr>
                <td align="left">Saldo Awal</td>
                <td>:</td>
                <td>{{number_format(abs($saldo_awal),0,'.',',')}} ({{$posisi}})</td>
            </tr>
        </table>
        <br>
        <br>
        <table class="table table-bordered" id="main_table">
            <thead>
               <tr>
                   <th class="text-center" >No.</th>
                   <th class="text-center" >Tanggal.</th>
                   <th class="text-center" >Deskripsi</th>
                   <th class="text-center" >Debet</th>
                   <th class="text-center" >Kredit</th>
                </tr>   
            </thead>
            <tbody>
               <?php
                $index = 1;
                $g_dr = 0;
                $g_cr = 0;
                $saldo_akhir = $saldo_awal;
               ?>
               @foreach ($result as $r)
               <?php
                 $g_dr += $r->dr;
                 $g_cr += $r->cr;

                 if($normal == 'D'){
                    $saldo_akhir += ($r->dr - $r->cr);
                 }else if ($normal == 'C'){
                    $saldo_akhir += ($r->cr - $r->dr);
                 }
               ?>
               <tr>
                   <td class="text-center" >{{$index++}}</td>
                   <td class="text-center" >{{date('d-m-Y', strtotime( $r->tanggal )) }}</td>
                   <td>{{$r->narasi}}</td>
                   <td align="right" >{{number_format($r->dr,0,'.',',')}}</td>
                   <td align="right" >{{number_format($r->cr,0,'.',',')}}</td>
               </tr>
               @endforeach 
               <tr>
                   <td align="right" colspan="3">TOTAL</td>
                   <td align="right" >{{number_format($g_dr,0,'.',',')}}</td>
                   <td align="right" >{{number_format($g_cr,0,'.',',')}}</td>
               </tr>
               <tr>
                    <?php 
                        $posisi_akhir = $normal;
                        if ($saldo_akhir < 0){
                            if ($normal == 'D'){
                                $posisi_akhir = 'C';
                            }else if  ($normal == 'C'){
                                $posisi_akhir = 'D';
                            }
                        }

                    ?>
                   <td align="right" colspan="4">Saldo Akhir</td> 
                   <td align="right" >{{number_format(abs($saldo_akhir),0,'.',',')}} ( {{ $posisi_akhir }} )</td>
               </tr>
            </tbody>
        </table>
    </body>
</html>

   

