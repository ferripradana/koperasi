<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Supplier;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;


use Session;

class SupplierController extends Controller
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
           $supplier = Supplier::with('bank')->select('supplier.*');
            return Datatables::of($supplier)
                   ->addColumn('action', function($supplier){
                        return view('datatable._action',[
                                'model' => $supplier,
                                'form_url' => route('supplier.destroy', $supplier->id),
                                'edit_url' => route('supplier.edit', $supplier->id),
                                'show_url' => route('supplier.show', $supplier->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $supplier->nama . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'kode_supplier', 'name' => 'kode_supplier', 'title' => 'Kode Supplier'])
            ->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.supplier.index')->with(compact('html'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.supplier.create');
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
         //
        $this->validate($request,[
            'nama' => 'required|min:3|unique:supplier',
            'kode_supplier' => 'required|unique:supplier',
        ],[]);

        $supplier = Supplier::create($request->all());

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil menyimpan $supplier->nama"
        ]);

        return redirect()->route('supplier.index');


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
        $supplier = Supplier::find($id);
        return view('admin.supplier.edit')->with(compact('supplier'));
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
            'nama' => 'required|min:3|unique:supplier,nama,'.$id,
            'kode_supplier' => 'required|unique:supplier,kode_supplier,'.$id,
        ],[]);

        $supplier = Supplier::find($id);
        if(!$supplier->update($request->all())) return redirect()->back();


        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil menyimpan $supplier->nama"
        ]);

        return redirect()->route('supplier.index');
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
         $supplier = Supplier::find($id);
     
        if (!Supplier::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Anggota berhasil dihapus"
        ]);
        return redirect()->route('supplier.index');
    }
}
