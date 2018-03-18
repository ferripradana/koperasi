<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Jabatan;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;

use Excel;
use Illuminate\Support\Facades\Input;

class JabatanController extends Controller
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
           $jabatans = Jabatan::all();
            return Datatables::of($jabatans)
                   ->addColumn('action', function($jabatan){
                        return view('datatable._action',[
                                'model' => $jabatan,
                                'form_url' => route('jabatan.destroy', $jabatan->id),
                                'edit_url' => route('jabatan.edit', $jabatan->id),
                                'show_url' => route('jabatan.show', $jabatan->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $jabatan->nama_jabatan . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'nama_jabatan', 'name' => 'nama_jabatan', 'title' => 'Nama Jabatan'])
            ->addColumn(['data' => 'plafone', 'name' => 'plafone', 'title' => 'Plafon'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.jabatan.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.jabatan.create');
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
            'nama_jabatan' => 'required|min:3|unique:jabatan',
            'plafon' => 'required|numeric',
        ],[]);

        $jabatan = Jabatan::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$jabatan->nama_jabatan,
            ]
        );


        return redirect()->route('jabatan.index');
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
        $jabatan = Jabatan::find($id);
        return view('admin.jabatan.edit')->with(compact('jabatan'));
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
            'nama_jabatan' => 'required|min:3|unique:jabatan,nama_jabatan,'.$id,
            'plafon' => 'required|numeric',
        ],[]);

        $jabatan = Jabatan::find($id);
        $jabatan->update($request->all());
        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$jabatan->nama_jabatan,
            ]
        );


        return redirect()->route('jabatan.index');
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
          if (!Jabatan::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Jabatan berhasil dihapus"
        ]);
        return redirect()->route('jabatan.index');
    }

    public function importAction(){
        if(Input::hasFile('import')){
            $path = Input::file('import')->getRealPath();
            $rows = Excel::load($path, function($reader) {
                })->get();

            $i =0;
            foreach ($rows as $row) {
                $data = $row->toArray();

                Jabatan::create($data);
                $i++;
            }

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Berhasil mengimport ".$i." jabatan",
            ]);

        }

        return redirect()->route('jabatan.index');
    }
}
