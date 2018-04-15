<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Anggota Per Unit Kerja</title>
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
    <?php 
        $unit = \App\Model\Unit::orderBy('name')->get();
        $gt = [];
        foreach ($unit as $u ) {
            $gt[$u->name]['aktif'] = 0;
            $gt[$u->name]['non_aktif'] = 0;
        }
    ?>
    <body>
        <h1 class="text-center">Report Saldo Per anggota</h1>
        <br>
        <br>
        <table class="table table-bordered" id="main_table">
            <thead>
                <tr>
                    <th align="left" colspan="7" >Aktif</th>
                </tr>
                <tr>
                    <th class="text-center" >Departemen</th>
                    <th class="text-center" >Unit Kerja</th>
                    <th class="text-center">NIK</th>
                    <th class="text-center">Jabatan/Level</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Alamat</th>
                    <th class="text-center">No. Telp</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 0 ;
                    $count = 0;
                ?>
                @foreach ($aktif as $r)
                <?php 
                    $gt[$r->unit]['aktif'] +=1;
                 ?>
                <tr>
                    <td align="left">{{$r->departemen}}</td>
                    <td align="left">{{$r->unit}}</td>
                    <td align="left">{{$r->nik}}</td>
                    <td align="left">{{$r->nama_jabatan}}</td>
                    <td align="left">{{$r->nama}}</td>
                    <td align="left">{{$r->alamat}}</td>
                    <td align="left">{{$r->phone}}</td>
                </tr>
                <?php 
                    $i++ ;
                    $count ++;
                ?>
                 @if( (isset($aktif[$i]) && ($aktif[$i]->unit != $r->unit)) or (count($aktif) == $i ) )
                    <tr>
                      <td align="left">&nbsp;</td>
                       <td align="left"><b>Sub Total {{ $r->unit }} </b></td>
                      <td align="left">{{ number_format($count,0,'.',',') }}</td>
                      <td colspan="4">&nbsp;</td>
                    </tr>
                    
                    <?php
                        $count = 0;
                       $sub_saldo = 0;
                    ?>
                  @endif
                 @endforeach
            </tbody>
        </table>    
        <br>
        <br>
        <table class="table table-bordered" id="main_table">
            <thead>
                <tr>
                    <th align="left" colspan="7" >Non Aktif</th>
                </tr>
                <tr>
                    <th class="text-center" >Departemen</th>
                    <th class="text-center" >Unit Kerja</th>
                    <th class="text-center">NIK</th>
                    <th class="text-center">Jabatan/Level</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Alamat</th>
                    <th class="text-center">No. Telp</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 0 ;
                    $count = 0;
                ?>
                @foreach ($non_aktif as $r)
                <?php 
                    $gt[$r->unit]['non_aktif'] +=1;
                 ?>
                <tr>
                    <td align="left">{{$r->departemen}}</td>
                    <td align="left">{{$r->unit}}</td>
                    <td align="left">{{$r->nik}}</td>
                    <td align="left">{{$r->nama_jabatan}}</td>
                    <td align="left">{{$r->nama}}</td>
                    <td align="left">{{$r->alamat}}</td>
                    <td align="left">{{$r->phone}}</td>
                </tr>
                <?php 
                    $i++ ;
                    $count ++;
                ?>
                 @if( (isset($aktif[$i]) && ($aktif[$i]->unit != $r->unit)) or (count($aktif) == $i ) )
                    <tr>
                      <td align="left">&nbsp;</td>
                       <td align="left"><b>Sub Total {{ $r->unit }} </b></td>
                      <td align="left">{{ number_format($count,0,'.',',') }}</td>
                      <td colspan="4">&nbsp;</td>
                    </tr>
                    
                    <?php
                        $count = 0;
                       $sub_saldo = 0;
                    ?>
                  @endif
                 @endforeach
            </tbody>
        </table>    
        
        <br>
        <br>
        <table class="table table-bordered table-nonfluid" id="main_table">
            <tr>
                <th>Unit</th>    
                <th>AKtif</th>
                <th>Non Aktif</th>
            </tr>
            <?php 
                $jaktif = 0;
                $jnon_aktif = 0;
            ?>
            @foreach ($gt as $k => $v)
            <tr>
                <td>{{$k}}</td>    
                <td>{{number_format($v['aktif'],0,'.',',') }}</td>
                <td>{{number_format($v['non_aktif'],0,'.',',') }}</td>
                <?php 
                     $jaktif += $v['aktif'];
                     $jnon_aktif += $v['non_aktif'];
                 ?>
            </tr>
            @endforeach
            <tr>
                <td><b>Grand Total</b></td>    
                <td><b>{{ number_format($jaktif,0,'.',',')  }}</b></td>
                <td><b>{{ number_format($jnon_aktif ,0,'.',',')  }}</b></td>
            </tr>
        </table>

    </body>
    </html>
