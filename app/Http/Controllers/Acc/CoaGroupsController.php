<?php

namespace App\Http\Controllers\Acc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Acc\CoaGroup;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;


use Session;


class CoaGroupsController extends Controller
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
           $groups = CoaGroup::all();
            return Datatables::of($groups)
                   ->addColumn('action', function($group){
                        return view('datatable._action',[
                                'model' => $group,
                                'form_url' => route('coagroups.destroy', $group->id),
                                'edit_url' => route('coagroups.edit', $group->id),
                                'show_url' => route('coagroups.show', $group->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $group->nama . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'code', 'name' => 'code', 'title' => 'Kode'])
             ->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.coagroup.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.coagroup.create');
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
            'code' => 'required|min:1|unique:coa_groups',
            'nama' => 'required|min:3|unique:coa_groups',
            'description' => 'required',
            'balance_sheet_group' => 'required',
            'balance_sheet_side' => 'required',
            'pls_group'          => 'required',
            'pls_side'          => 'required',
        ],[]);

         $group = CoaGroup::create($request->all());

         Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$group->nama,
            ]
        );


        return redirect()->route('coagroups.index');

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
        //\
         $group = CoaGroup::find($id);
        return view('admin.coagroup.edit')->with(compact('group'));
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
            'code' => 'required|min:1|unique:coa_groups,code,'.$id,
            'nama' => 'required|min:3|unique:coa_groups,nama,'.$id,
            'description' => 'required',
            'balance_sheet_group' => 'required',
            'balance_sheet_side' => 'required',
            'pls_group'          => 'required',
            'pls_side'          => 'required',
        ],[]);

         $group = CoaGroup::find($id);
         $group->update($request->all());

         Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$group->nama,
            ]
        );


        return redirect()->route('coagroups.index');
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
        if (!CoaGroup::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Group COA berhasil dihapus"
        ]);
        return redirect()->route('coagroups.index');
    }
    }
}
