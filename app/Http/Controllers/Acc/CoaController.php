<?php

namespace App\Http\Controllers\Acc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Model\Acc\CoaGroup;
use App\Model\Acc\Coa;


use App\Helpers\Common;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;

use Session;

class CoaController extends Controller
{

    protected $helper;

    public function __construct(Common $helper){
        $this->helper = $helper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $coa = COA::all();
         $coa = $this->helper->createTree($coa);
         $helper = $this->helper;
         return view('admin.coa.index')->with(compact('coa', 'helper'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $parent = COA::select(
                    \DB::raw("CONCAT(code,'-',nama) AS name"),'id','parent_id')
                        ->orderBy('code','asc')
                        ->pluck('name', 'id', 'parent_id');
         return view('admin.coa.create')->with(compact('parent'));
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
            'code' => 'required|min:1|unique:coa',
            'nama' => 'required|min:3',
            'group_id' => 'required',
            'parent_id' => 'required',
            'balance_sheet_group' => 'required',
            'balance_sheet_side' => 'required',
            'pls_group'          => 'required',
            'pls_side'          => 'required',
        ],[]);

         $coa = Coa::create($request->all());

         Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$coa->nama,
            ]
        );


        return redirect()->route('coa.index');
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
         $coa = Coa::find($id);
         $parent = COA::select(
                    \DB::raw("CONCAT(code,'-',nama) AS name"),'id','parent_id')
                        ->orderBy('code','asc')
                        ->where('id','!=',$id)
                        ->pluck('name', 'id', 'parent_id');
        return view('admin.coa.edit')->with(compact('coa', 'parent'));
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
            'code' => 'required|min:1|unique:coa,code,'.$id,
            'nama' => 'required|min:3',
            'group_id' => 'required',
            'parent_id' => 'required',
            'balance_sheet_group' => 'required',
            'balance_sheet_side' => 'required',
            'pls_group'          => 'required',
            'pls_side'          => 'required',
        ],[]);

         $coa = Coa::find($id);
         $coa->update($request->all());

         Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$coa->nama,
            ]
        );


        return redirect()->route('coa.index');
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
    }
}
