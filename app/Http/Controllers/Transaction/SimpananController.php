<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Simpanan;
use App\Model\JenisSimpanan;
use App\Model\Anggota;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;

use Session;


class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
           $simpanans = Simpanan::with('anggota', 'jenissimpanan');
            return Datatables::of($simpanans)
                   ->addColumn('action', function($simpanan){
                        return view('datatable._action',[
                                'model' => $simpanan,
                                'form_url' => route('simpanan.destroy', $simpanan->id),
                                'edit_url' => route('simpanan.edit', $simpanan->id),
                                'show_url' => route('simpanan.show', $simpanan->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $simpanan->no_transaction . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'no_transaksi', 'name' => 'no_transaksi', 'title' => 'Nomor Transaksi'])
            ->addColumn(['data' => 'anggota.nama', 'name' => 'anggota.nama', 'title' => 'Nama Anggota'])
            ->addColumn(['data' => 'jenissimpanan.nama_simpanan', 'name' => 'jenissimpanan.nama_simpanan', 'title' => 'Jenis Simpanan'])
            ->addColumn(['data' => 'tanggal_transaksi', 'name' => 'tanggal_transaksi', 'title' => 'Tanggal Transaksi'])
            ->addColumn(['data' => 'nominalview', 'name' => 'nominal', 'title' => 'Nominal'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.simpanan.index')->with(compact('html'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.simpanan.create');
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
            'no_transaksi' => 'required',
            'id_anggota' => 'required|numeric',
            'id_simpanan' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal_transaksi' => 'required',
        ],[]);

        $simpanan = Simpanan::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan simpanan '.$simpanan->no_transaksi,
            ]
        );


        return redirect()->route('simpanan.index');
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
        $simpanan = Simpanan::find($id);
        return view('admin.simpanan.edit')->with(compact('simpanan'));
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
            'no_transaksi' => 'required',
            'id_anggota' => 'required|numeric',
            'id_simpanan' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal_transaksi' => 'required',
        ],[]);

        $simpanan = Simpanan::find($id);
         $simpanan->update($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil mengedit simpanan '.$simpanan->no_transaksi,
            ]
        );


        return redirect()->route('simpanan.index');
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
        if (!Simpanan::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Transaksi Simpanan berhasil dihapus"
        ]);
        return redirect()->route('simpanan.index');
    }
}
