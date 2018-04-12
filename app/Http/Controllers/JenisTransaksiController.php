<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\JenisTransaksi;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;


use Session;

class JenisTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        //
        if ($request->ajax()) {
           $jenistransaksis = JenisTransaksi::all();
            return Datatables::of($jenistransaksis)
                   ->addColumn('action', function($jenistransaksi){
                        return view('datatable._action',[
                                'model' => $jenistransaksi,
                                'form_url' => route('jenistransaksi.destroy', $jenistransaksi->id),
                                'edit_url' => route('jenistransaksi.edit', $jenistransaksi->id),
                                'show_url' => route('jenistransaksi.show', $jenistransaksi->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $jenistransaksi->nama_transaksi . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'nama_transaksi', 'name' => 'nama_transaksi', 'title' => 'Nama'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.jenistransaksi.index')->with(compact('html'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.jenistransaksi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'nama_transaksi' => 'required|min:3|unique:jenis_transaksi',
            'type' => 'required|numeric',
        ],[]);

        $js = JenisTransaksi::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$js->nama_transaksi,
            ]
        );


        return redirect()->route('jenistransaksi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return JenisTransaksi::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $jenistransaksi = JenisTransaksi::find($id);
        return view('admin.jenistransaksi.edit')->with(compact('jenistransaksi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request,[
            'nama_transaksi' => 'required|min:3|unique:jenis_transaksi,nama_transaksi,'.$id,
            'type' => 'required|numeric',
        ],[]);

        $js = JenisTransaksi::find($id);
        $js->update($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$js->nama_transaksi,
            ]
        );


        return redirect()->route('jenistransaksi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (!JenisTransaksi::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Jenis Transaksi berhasil dihapus"
        ]);
        return redirect()->route('jenistransaksi.index');
    }
}
