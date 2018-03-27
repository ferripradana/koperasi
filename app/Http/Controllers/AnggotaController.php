<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Anggota;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;


use Session;




class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
           $anggotas = Anggota::with('unit')->select('anggota.*');
            return Datatables::of($anggotas)
                   ->addColumn('action', function($anggota){
                        return view('datatable._action',[
                                'model' => $anggota,
                                'form_url' => route('anggotas.destroy', $anggota->id),
                                'edit_url' => route('anggotas.edit', $anggota->id),
                                'show_url' => route('anggotas.show', $anggota->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $anggota->nama . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'nik', 'name' => 'nik', 'title' => 'NIK'])
            ->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'])
            ->addColumn(['data' => 'unit.name', 'name' => 'unit.name', 'title' => 'Unit Kerja'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.anggotas.index')->with(compact('html'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.anggotas.create');
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
            'nama' => 'required|min:3|unique:anggota',
            'nia' => 'required|unique:anggota',
            // 'nik' => 'required|unique:anggota',
            'no_ktp' => 'required|unique:anggota',
        ],[]);

        $anggota = Anggota::create($request->except('foto'));

        if ($request->hasFile('foto')) {

            // Mengambil file yang diupload
            $uploaded_cover = $request->file('foto');

            // Mengambil extension file
            $extension = $uploaded_cover->getClientOriginalExtension();

            // Membuat nama file random berikut extension
            $filename = md5(time()) . "." . $extension;

            // Menyimpan cover ke folder public/img
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_cover->move($destinationPath, $filename);

            // Mengisi field cover di book dengan filename yang baru dibuat
            $anggota->foto = $filename;
            $anggota->save();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil menyimpan $anggota->nama"
        ]);

        return redirect()->route('anggotas.index');


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
        $anggota = Anggota::find($id);
        return view('admin.anggotas.edit')->with(compact('anggota'));
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
            'nama' => 'required|min:3|unique:anggota,nama,'.$id,
            'nia' => 'required|unique:anggota,nia,'.$id,
            //'nik' => 'required|unique:anggota,nik,'.$id,
            'no_ktp' => 'required|unique:anggota,no_ktp,'.$id,
        ],[]);

        $anggota = Anggota::find($id);
        if(!$anggota->update($request->except('foto'))) return redirect()->back();

        if ($request->hasFile('foto')) {

            // Mengambil cover yang diupload berikut ektensinya
            $filename = null;
            $uploaded_cover = $request->file('foto');
            $extension = $uploaded_cover->getClientOriginalExtension();

            // Membuat nama file random dengan extension
            $filename = md5(time()) . '.' . $extension;

            // Menyimpan cover ke folder public/img
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_cover->move($destinationPath, $filename);

            // Hapus cover lama, jika ada
            if ($anggota->foto) {
                $old_cover = $anggota->foto;
                $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $anggota->foto;

                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                    // File sudah dihapus/tidak ada
                }
            }

            // Ganti field cover dengan cover yang baru
            $anggota->foto = $filename;
            $anggota->save();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil menyimpan $anggota->nama"
        ]);

        return redirect()->route('anggotas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $anggota = Anggota::find($id);
     
        if (!Anggota::destroy($id)) {
            return redirect()->back();
        }

        if ($anggota->foto) {
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $anggota->foto;

            try {
                File::delete($filepath);
            } catch (FileNotFoundException $e) {
                // File sudah dihapus/tidak ada
            }
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Anggota berhasil dihapus"
        ]);
        return redirect()->route('anggotas.index');
    }


    public function import(){
        return view('admin.anggotas.import');
    }

    public function importAction(){
        if(Input::hasFile('import')){
            $path = Input::file('import')->getRealPath();
            $rows = Excel::load($path, function($reader) {
                })->get();

            $i =0;
            foreach ($rows as $row) {
                $data = $row->toArray();

                Anggota::create($data);
                $i++;
            }

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Berhasil mengimport ".$i." anggota",
            ]);

        }

        return redirect()->route('anggotas.index');
    }


}
