<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Department;
use App\Model\Unit;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;
use Excel;
use Illuminate\Support\Facades\Input;


class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
          if ($request->ajax()) {
           $units = Unit::with('department')->select('units.*');
            return Datatables::of($units)
                   ->addColumn('action', function($unit){
                        return view('datatable._action',[
                                'model' => $unit,
                                'form_url' => route('units.destroy', $unit->id),
                                'edit_url' => route('units.edit', $unit->id),
                                'show_url' => route('units.show', $unit->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $unit->name . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
            ->addColumn(['data' => 'department.name', 'name' => 'department.name', 'title' => 'Nama Department'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.units.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.units.create');
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
            'name' => 'required|min:3|unique:units',
            'department_id' => 'required',
        ],[]);

        $unit = Unit::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$unit->name,
            ]
        );


        return redirect()->route('units.index');
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
        $unit = Unit::find($id);
        return view('admin.units.edit')->with(compact('unit'));
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
            'name' => 'required|min:3|unique:units,name,'.$id,
            'department_id' => 'required',
        ],[]);

        $unit = Unit::find($id);
        $unit->update($request->all());
        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$unit->name,
            ]
        );


        return redirect()->route('units.index');
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
        if (!Unit::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Unit berhasil dihapus"
        ]);
        return redirect()->route('units.index');
    }

    public function importAction(){
        if(Input::hasFile('import')){
            $path = Input::file('import')->getRealPath();
            $rows = Excel::load($path, function($reader) {
                })->get();

            $i =0;
            foreach ($rows as $row) {
                $data = $row->toArray();

                Unit::create($data);
                $i++;
            }

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Berhasil mengimport ".$i." unit",
            ]);

        }

        return redirect()->route('units.index');
    }
}
