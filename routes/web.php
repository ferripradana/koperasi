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
		});

	Route::group(['prefix'=>'admin/transaction',
		'middleware'=>['auth']],function(){
			Route::get('simpanan/viewanggota','Transaction\SimpananController@viewAnggota')->name('simpanan.viewanggota');
			Route::get('simpanan/viewtabungan','Transaction\SimpananController@viewTabungan')->name('simpanan.viewtabungan');
			Route::resource('simpanan','Transaction\SimpananController');

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

			Route::resource('coagroups','Acc\CoaGroupsController');
			Route::resource('coa','Acc\CoaController');
			
			Route::group(['prefix' => 'common'], function () {
                Route::get('import/{type}', 'Common\ImportController@create')->name('common.import');
            });

		});






});