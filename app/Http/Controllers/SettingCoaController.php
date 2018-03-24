<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Settingcoa;

use Session;


class SettingCoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $peminjaman_debit  = Settingcoa::where('transaksi','peminjaman_debit')->select('id_coa')->first();
        $peminjaman_credit =  Settingcoa::where('transaksi','peminjaman_credit')->select('id_coa')->first() ;

        return view('admin.settingcoa.index')->with(compact('peminjaman_credit', 'peminjaman_debit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        Settingcoa::query()->delete();
        $peminjaman_debit = Settingcoa::create(
            [
                'transaksi' => 'peminjaman_debit',
                'id_coa'    => $request->peminjaman_debit,
            ]
        );

        $peminjaman_debit = Settingcoa::create(
            [
                'transaksi' => 'peminjaman_credit',
                'id_coa'    => $request->peminjaman_credit,
            ]
        );

         Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil menyimpan Setting"
        ]);

        return redirect()->route('settingcoa.index');
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
