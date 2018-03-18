<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\KeteranganPinjaman;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;

use Excel;
use Illuminate\Support\Facades\Input;



class KeteranganPinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
         if ($request->ajax()) {
           $keterangans = KeteranganPinjaman::all();
            return Datatables::of($keterangans)
                   ->addColumn('action', function($keterangan){
                        return view('datatable._action',[
                                'model' => $keterangan,
                                'form_url' => route('keteranganpinjaman.destroy', $keterangan->id),
                                'edit_url' => route('keteranganpinjaman.edit', $keterangan->id),
                                'show_url' => route('keteranganpinjaman.show', $keterangan->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $keterangan->guna_pinjaman . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'guna_pinjaman', 'name' => 'guna_pinjaman', 'title' => 'Guna Pinjaman'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.keterangan.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.keterangan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validate($request,[
            'guna_pinjaman' => 'required|min:3|unique:keterangan_pinjaman',
        ],[]);

        $keterangan_pinjaman = KeteranganPinjaman::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$keterangan_pinjaman->guna_pinjaman,
            ]
        );


        return redirect()->route('keteranganpinjaman.index');
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
        $keterangan = KeteranganPinjaman::find($id);
        return view('admin.keterangan.edit')->with(compact('keterangan'));
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
         //
         $this->validate($request,[
            'guna_pinjaman' => 'required|min:3|unique:keterangan_pinjaman,guna_pinjaman,'.$id,
        ],[]);

        $ketarangan = KeteranganPinjaman::find($id);
        $ketarangan->update($request->all());
        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$ketarangan->guna_pinjaman,
            ]
        );


        return redirect()->route('keteranganpinjaman.index');
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
        if (!KeteranganPinjaman::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Keterangan pinjaman berhasil dihapus"
        ]);
        return redirect()->route('keteranganpinjaman.index');
    }

     public function importAction(){
        if(Input::hasFile('import')){
            $path = Input::file('import')->getRealPath();
            $rows = Excel::load($path, function($reader) {
                })->get();

            $i =0;
            foreach ($rows as $row) {
                $data = $row->toArray();

                KeteranganPinjaman::create($data);
                $i++;
            }

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Berhasil mengimport ".$i." keterangan pinjaman",
            ]);

        }

        return redirect()->route('keteranganpinjaman.index');
    }
}
