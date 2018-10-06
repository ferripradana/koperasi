<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Bank;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;

use Excel;
use Illuminate\Support\Facades\Input;



class BankController extends Controller
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
           $bank = Bank::select(['id','name']);
            return Datatables::of($bank)
                   ->addColumn('action', function($bank){
                        return view('datatable._action',[
                                'model' => $bank,
                                'form_url' => route('bank.destroy', $bank->id),
                                'edit_url' => route('bank.edit', $bank->id),
                                'show_url' => route('bank.show', $bank->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $bank->name . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.bank.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.bank.create');
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
            'name' => 'required|min:3|unique:bank',
        ],[]);

        $bank = Bank::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$bank->name,
            ]
        );


        return redirect()->route('bank.index');
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
        $bank = Bank::find($id);
        return view('admin.bank.edit')->with(compact('bank'));
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
            'name' => 'required|min:3|unique:departments,name,'.$id,
        ],[]);

        $bank = Bank::find($id);
        $bank->update($request->all());
        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$bank->name,
            ]
        );


        return redirect()->route('bank.index');
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
        if (!Bank::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Department berhasil dihapus"
        ]);
        return redirect()->route('bank.index');
    }
}
