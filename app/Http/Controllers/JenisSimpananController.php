<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\JenisSimpanan;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;


use Session;

class JenisSimpananController extends Controller
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
           $jenissimpanans = JenisSimpanan::all();
            return Datatables::of($jenissimpanans)
                   ->addColumn('action', function($jenissimpanan){
                        return view('datatable._action',[
                                'model' => $jenissimpanan,
                                'form_url' => route('jenissimpanan.destroy', $jenissimpanan->id),
                                'edit_url' => route('jenissimpanan.edit', $jenissimpanan->id),
                                'show_url' => route('jenissimpanan.show', $jenissimpanan->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $jenissimpanan->nama_simpanan . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'nama_simpanan', 'name' => 'nama_simpanan', 'title' => 'Nama'])
            ->addColumn(['data' => 'minimum', 'name' => 'minimum', 'title' => 'Nominal Minimum'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.jenissimpanan.index')->with(compact('html'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.jenissimpanan.create');
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
            'nama_simpanan' => 'required|min:3|unique:jenis_simpanan',
            'nominal_minimum' => 'required|numeric',
        ],[]);

        $js = JenisSimpanan::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$js->nama_simpanan,
            ]
        );


        return redirect()->route('jenissimpanan.index');
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
        $jenissimpanan = JenisSimpanan::find($id);
        return view('admin.jenissimpanan.edit')->with(compact('jenissimpanan'));
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
            'nama_simpanan' => 'required|min:3|unique:jenis_simpanan,nama_simpanan,'.$id,
            'nominal_minimum' => 'required|numeric',
        ],[]);

        $js = JenisSimpanan::find($id);
        $js->update($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$js->nama_simpanan,
            ]
        );


        return redirect()->route('jenissimpanan.index');
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
        if (!JenisSimpanan::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Jenis Simpanan berhasil dihapus"
        ]);
        return redirect()->route('jenissimpanan.index');
    }
}
