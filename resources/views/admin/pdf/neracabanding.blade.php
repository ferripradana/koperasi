<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Perbandingan Neraca</title>
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
        <h1 class="text-center">Report Perbandingan Neraca</h1>
        <br>
        <br>
        <table class="table table-nonfluid borderless">
            <tr>
                <td align="left">Periode</td>
                <td>:</td>
                <td>{{$bulan_from}} / {{$tahun_from}} - {{$bulan_to}} / {{$tahun_to}} </td>
            </tr>
        </table>
        <br>
        <br>
        <table class="table table-bordered" id="main_table">
            <thead>
               <tr>
                   <th class="text-center" >Debet</th>
                   <th class="text-center" >Kredit</th>
                </tr>   
            </thead>
            <tbody>
                <tr>
                    <td>
                        <b>Aktiva</b>
                        <table width="100%">
                            <tr>
                                <th>Kode Nama Akun</th>
                                <th class='text-right'>{{$bulan_from}} / {{$tahun_from}}</th>
                                <th class='text-right'>{{$bulan_to}} / {{$tahun_to}}</th>
                                <th class='text-right'>%</th>
                            </tr>
                            <?php 
                                printTree($coa_asset);
                            ?>  
                        </table>
                    </td>
            
                    <td>
                        <b>Pasiva</b>
                        <table width="100%">
                            <tr>
                                <th>Kode Nama Akun</th>
                                <th class='text-right'>{{$bulan_from}} / {{$tahun_from}}</th>
                                <th class='text-right'>{{$bulan_to}} / {{$tahun_to}}</th>
                                <th class='text-right'>%</th>
                            </tr>
                            <?php 
                                printTree($coa_l);
                            ?>  
                        </table>
                        <br>
                        <table width="100%">
                            <tr>
                                <th>Kode Nama Akun</th>
                                <th class='text-right'>{{$bulan_from}} / {{$tahun_from}}</th>
                                <th class='text-right'>{{$bulan_to}} / {{$tahun_to}}</th>
                                <th class='text-right'>%</th>
                            </tr>
                            <?php 
                                printTree($coa_e);
                            ?>  
                        </table>
                        <br>
                        <b>Laba Rugi</b>
                        <table width="100%">
                        <tr>
                             <td class='text-right'>RL {{$bulan_from}} / {{$tahun_from}} </td>
                             <td class='text-right'>RL {{$bulan_to}} / {{$tahun_to}} </td>
                             <td class='text-right'>%</td>
                        </tr>
                        <tr>
                            <td class='text-right'>
                                {{ number_format($profit1) }}
                            </td>
                            <td class="text-right">
                                {{ number_format($profit2) }}
                            </td>
                            <td class="text-right">
                                <?php 
                                    if ($profit1==0) {
                                        $persen = 0;
                                    }else{
                                        $persen = ($profit1 - $profit2)/$profit1*100;
                                    }
                                    
                                ?>
                                {{ number_format($persen,2) }} %
                            </td>
                        </tr>
                        </table>
                    </td>
                
            </tbody>
        </table>
    </body>
    </html>

    <?php
    function printTree($arr) {
        if(!is_null($arr) && count($arr) > 0) {
            echo '<tr>';
            foreach($arr as $node) {
                echo "<td>".str_repeat(' _ ',strlen($node['code'])) .  $node['sect_name']."</td><td class='text-right'>". number_format($node['amount_from']).'</td><td class="text-right">'.number_format($node['amount_to']).'</td><td class="text-right">'.number_format($node['persen'],2)." %" ;
                if (array_key_exists('children', $node)) {
                    echo "</td></tr>";
                    printTree($node['children']);
                }
                echo '</td></tr>';
            }
            echo '</tr>';
        }
       // echo "<tr><td colspan='3'></td></tr>"
    }

    ?>

