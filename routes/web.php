<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/', 'HomeController@index');

Route::group(['midlleware'=>'web'], function(){
	Route::group(['prefix'=>'admin',
		'middleware'=>['auth', 'role:superadmin']],function(){
			Route::resource('users','UsersController');
			Route::resource('roles','RolesController');
			Route::resource('settingcoa','SettingCoaController');
		});

	Route::group(['prefix'=>'admin/transaction',
		'middleware'=>['auth']],function(){
			Route::get('simpanan/viewanggota','Transaction\SimpananController@viewAnggota')->name('simpanan.viewanggota');
			Route::get('simpanan/viewtabungan','Transaction\SimpananController@viewTabungan')->name('simpanan.viewtabungan');
			Route::resource('simpanan','Transaction\SimpananController');

			Route::get('penarikan/viewsaldo','Transaction\PenarikanController@viewSaldo')->name('penarikan.viewsaldo');
			Route::resource('penarikan', 'Transaction\PenarikanController');


			Route::resource('lain', 'Transaction\TransaksiLainController');
			Route::get('shu', 'Transaction\ShuController@index')->name('shu.index');
			Route::post('shuform', 'Transaction\ShuController@create')->name('shu.form');
			Route::post('shusave', 'Transaction\ShuController@simpan')->name('shu.save');
	});

	Route::group(['prefix'=>'admin/report',
		'middleware'=>['auth']],function(){
			Route::get('reportpeminjaman', 'Report\ReportPinjamanController@index')->name('reportpeminjaman.index');
			Route::get('reportviewpeminjaman', 'Report\ReportPinjamanController@viewpeminjaman')->name('reportpeminjaman.viewpeminjaman');
			Route::post('reportpeminjamanpost', 'Report\ReportPinjamanController@post')->name('reportpeminjaman.reportpeminjamanpost');

			Route::get('reportrekap', 'Report\ReportRekapController@index')->name('reportrekap.index');
			Route::get('reportrekapunit', 'Report\ReportRekapController@unit')->name('reportrekap.unit');
			Route::post('reportrekappost', 'Report\ReportRekapController@post')->name('reportrekap.post');
	

			Route::get('reportbulanrekap', 'Report\ReportRekapBulanController@index')->name('reportbulanrekap.index');
			Route::get('reportbulanrekaptanggal', 'Report\ReportRekapBulanController@tanggal')->name('reportbulanrekap.reportbulanrekaptanggal');
			Route::post('reportbulanrekappost', 'Report\ReportRekapBulanController@post')->name('reportbulanrekap.post');


			Route::get('reportsaldoperanggota', 'Report\ReportSaldoPerAnggota@index')->name('reportsaldoperanggota.index');
			Route::post('reportsaldoperanggotapost', 'Report\ReportSaldoPerAnggota@post')->name('reportsaldoperanggota.post');


			Route::get('rekapperanggota', 'Report\ReportRekapPerAnggota@index')->name('rekapperanggota.index');
			Route::post('rekapperanggotapost', 'Report\ReportRekapPerAnggota@post')->name('rekapperanggota.post');

			Route::get('anggotaunitkerja', 'Report\ReportAnggota@index')->name('anggotaunitkerja.index');
			Route::post('anggotaunitkerjapost', 'Report\ReportAnggota@post')->name('anggotaunitkerja.post');


			Route::get('labarugi', 'Report\ReportLabaRugi@index')->name('labarugi.index');
			Route::post('labarugipost', 'Report\ReportLabaRugi@post')->name('labarugi.post');

			Route::get('labarugibanding', 'Report\ReportLabaRugiBanding@index')->name('labarugibanding.index');
			Route::post('labarugibandingpost', 'Report\ReportLabaRugiBanding@post')->name('labarugibanding.post');

			Route::get('neraca', 'Report\ReportNeraca@index')->name('neraca.index');
			Route::post('neracapost', 'Report\ReportNeraca@post')->name('neraca.post');

			Route::get('perbneraca', 'Report\ReportNeracaBanding@index')->name('perbneraca.index');
			Route::post('perbneracapost', 'Report\ReportNeracaBanding@post')->name('perbneraca.post');

			Route::get('entrypercoa', 'Report\ReportEntryCoa@index')->name('entrypercoa.index');
			Route::post('entrypercoapost', 'Report\ReportEntryCoa@post')->name('entrypercoa.post');

			Route::get('perhitunganshu', 'Report\ReportPerhitunganShu@index')->name('perhitunganshu.index');
			Route::post('perhitunganshupost', 'Report\ReportPerhitunganShu@post')->name('perhitunganshu.post');

			Route::get('shudibagi', 'Report\ReportPerhitunganShuDibagi@index')->name('shudibagi.index');
			Route::post('shudibagi', 'Report\ReportPerhitunganShuDibagi@post')->name('shudibagi.post');
	});

	Route::group(['prefix'=>'admin/loan',
		'middleware'=>['auth']],function(){

			Route::get('peminjaman/viewpeminjaman', 'Transaction\PeminjamanController@viewpeminjaman')->name('peminjaman.viewpeminjaman');
			Route::get('peminjaman/viewproyeksi','Transaction\PeminjamanController@viewproyeksi')->name('peminjaman.viewproyeksi');
			Route::resource('peminjaman', 'Transaction\PeminjamanController');


			Route::get('angsuran/viewpeminjaman', 'Transaction\AngsuranController@viewpeminjaman')->name('angsuran.viewpeminjaman');
			Route::get('angsuran/viewproyeksi', 'Transaction\AngsuranController@viewproyeksi')->name('angsuran.viewproyeksi');
			Route::get('angsuran/viewdetailproyeksi', 'Transaction\AngsuranController@viewdetailproyeksi')->name('angsuran.viewdetailproyeksi');
			Route::get('angsuran/createmassal', 'Transaction\AngsuranController@createmassal')->name('angsuran.createmassal');
			Route::get('angsuran/printmassal', 'Transaction\AngsuranController@printmassal')->name('angsuran.printmassal');
			Route::get('angsuran/viewjatuhtempo', 'Transaction\AngsuranController@viewjatuhtempo')->name('angsuran.viewjatuhtempo');
			Route::resource('angsuran', 'Transaction\AngsuranController');
			Route::post('angsuran/storemasal', 'Transaction\AngsuranController@storemasal')->name('angsuran.storemasal');
	

			Route::get('pinalti/viewproyeksi', 'Transaction\PinaltiController@viewproyeksi')->name('pinalti.viewproyeksi');
			Route::resource('pinalti', 'Transaction\PinaltiController');
	});

	Route::group(['prefix'=>'admin/master',
		'middleware'=>['auth']],function(){
			Route::resource('departments','DepartmentController');
			Route::post('department/importaction','DepartmentController@importAction')->name('department.importaction');

			Route::resource('units','UnitController');
			Route::post('unit/importaction','UnitController@importAction')->name('unit.importaction');


			Route::resource('anggotas','AnggotaController');
			Route::post('anggota/importaction','AnggotaController@importAction')->name('anggota.importaction');

			Route::resource('jabatan','JabatanController');
			Route::post('jabatan/importaction','JabatanController@importAction')->name('jabatan.importaction');

			Route::resource('keteranganpinjaman','KeteranganPinjamanController');
			Route::post('keteranganpinjaman/importaction','KeteranganPinjamanController@importAction')->name('keteranganpinjaman.importaction');

			Route::resource('jenissimpanan','JenisSimpananController');
			Route::resource('jenistransaksi','JenisTransaksiController');

			Route::resource('coagroups','Acc\CoaGroupsController');
			Route::resource('coa','Acc\CoaController');
			
			Route::group(['prefix' => 'common'], function () {
                Route::get('import/{type}', 'Common\ImportController@create')->name('common.import');
            });

		});






});