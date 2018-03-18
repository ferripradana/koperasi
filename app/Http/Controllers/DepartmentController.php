<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Department;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;

use Excel;
use Illuminate\Support\Facades\Input;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
           $departments = Department::select(['id','name']);
            return Datatables::of($departments)
                   ->addColumn('action', function($department){
                        return view('datatable._action',[
                                'model' => $department,
                                'form_url' => route('departments.destroy', $department->id),
                                'edit_url' => route('departments.edit', $department->id),
                                'show_url' => route('departments.show', $department->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $department->name . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.departments.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.departments.create');
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
            'name' => 'required|min:3|unique:departments',
        ],[]);

        $department = Department::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$department->name,
            ]
        );


        return redirect()->route('departments.index');
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
        $department = Department::find($id);
        return view('admin.departments.edit')->with(compact('department'));
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
            'name' => 'required|min:3|unique:departments,name,'.$id,
        ],[]);

        $department = Department::find($id);
        $department->update($request->all());
        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$department->name,
            ]
        );


        return redirect()->route('departments.index');
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
        if (!Department::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Department berhasil dihapus"
        ]);
        return redirect()->route('departments.index');
    }

    public function importAction(){
        if(Input::hasFile('import')){
            $path = Input::file('import')->getRealPath();
            $rows = Excel::load($path, function($reader) {
                })->get();

            $i =0;
            foreach ($rows as $row) {
                $data = $row->toArray();

                Department::create($data);
                $i++;
            }

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Berhasil mengimport ".$i." department",
            ]);

        }
    

        return redirect()->route('departments.index');
    }


}
