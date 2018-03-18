<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
         if ($request->ajax()) {
           $roles = Role::select(['id','name', 'display_name']);
            return Datatables::of($roles)
                   ->addColumn('action', function($role){
                        return view('datatable._action',[
                                'model' => $role,
                                'form_url' => route('roles.destroy', $role->id),
                                'edit_url' => route('roles.edit', $role->id),
                                'show_url' => route('roles.show', $role->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $role->name . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Name'])
        ->addColumn(['data' => 'display_name', 'name' => 'display_name', 'title' => 'Display Name'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.roles.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.roles.create');
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
            'name' => 'required|min:5|unique:roles',
            'display_name' => 'required'
        ],[]);

        $role = Role::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$role->name,
            ]
        );


        return redirect()->route('roles.index');
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
        $role = Role::find($id);
        return view('admin.roles.edit')->with(compact('role'));
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
            'name' => 'required|min:5|unique:roles,name,'.$id,
            'display_name' => 'required'
        ],[]);

        $role = Role::find($id);
        $role->update($request->all());
        
        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$role->name,
            ]
        );

        return redirect()->route('roles.index');
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
        if (!Role::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Role berhasil dihapus"
        ]);
        return redirect()->route('roles.index');
    
    }
}
