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

        $angsuran_debit  = Settingcoa::where('transaksi','angsuran_debit')->select('id_coa')->first();
        $angsuran_credit =  Settingcoa::where('transaksi','angsuran_credit')->select('id_coa')->first() ;

        $bunga_debit  = Settingcoa::where('transaksi','bunga_debit')->select('id_coa')->first();
        $bunga_credit =  Settingcoa::where('transaksi','bunga_credit')->select('id_coa')->first() ;

        $denda_debit  = Settingcoa::where('transaksi','denda_debit')->select('id_coa')->first();
        $denda_credit =  Settingcoa::where('transaksi','denda_credit')->select('id_coa')->first() ;

        $pinalti_debit  = Settingcoa::where('transaksi','pinalti_debit')->select('id_coa')->first();
        $pinalti_credit =  Settingcoa::where('transaksi','pinalti_credit')->select('id_coa')->first() ;

        return view('admin.settingcoa.index')->with(compact('peminjaman_credit', 'peminjaman_debit' ,
            'angsuran_credit', 'angsuran_debit', 'bunga_debit', 'bunga_credit', 'denda_debit', 'denda_credit', 'pinalti_debit', 'pinalti_credit'));
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

        $angsuran_debit = Settingcoa::create(
            [
                'transaksi' => 'angsuran_debit',
                'id_coa'    => $request->angsuran_debit,
            ]
        );

        $angsuran_credit = Settingcoa::create(
            [
                'transaksi' => 'angsuran_credit',
                'id_coa'    => $request->angsuran_credit,
            ]
        );

         $bunga_debit = Settingcoa::create(
            [
                'transaksi' => 'bunga_debit',
                'id_coa'    => $request->angsuran_debit,
            ]
        );

        $bunga_credit = Settingcoa::create(
            [
                'transaksi' => 'bunga_credit',
                'id_coa'    => $request->bunga_credit,
            ]
        );


        $denda_debit = Settingcoa::create(
            [
                'transaksi' => 'denda_debit',
                'id_coa'    => $request->denda_debit,
            ]
        );

        $denda_credit = Settingcoa::create(
            [
                'transaksi' => 'denda_credit',
                'id_coa'    => $request->denda_credit,
            ]
        );


        $pinalti_debit = Settingcoa::create(
            [
                'transaksi' => 'pinalti_debit',
                'id_coa'    => $request->pinalti_debit,
            ]
        );

        $pinalti_credit = Settingcoa::create(
            [
                'transaksi' => 'pinalti_credit',
                'id_coa'    => $request->pinalti_credit,
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
