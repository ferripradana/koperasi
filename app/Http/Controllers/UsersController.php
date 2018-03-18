<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;



class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
       if ($request->ajax()) {
           $users = User::select(['id','email','name']);
            return Datatables::of($users)
                   ->addColumn('action', function($user){
                        return view('datatable._action',[
                                'model' => $user,
                                'form_url' => route('users.destroy', $user->id),
                                'edit_url' => route('users.edit', $user->id),
                                'show_url' => route('users.show', $user->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $user->name . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
         ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
        ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email'])
        ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('admin.users.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.users.create');
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
        //print_r($request); die();
        $this->validate($request,[
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ],[]);

        $request->offsetSet('password', bcrypt($request->password));
        $user = User::create($request->except('password_confirmation'));

        if ($request->role) {
             $user->detachRoles();
             $role =  \App\Role::find($request->role);
             $user->attachRole($role);
        }

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$user->name,
            ]
        );

        return redirect()->route('users.index');
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
        $user = User::find($id);
        $roles = $user->roles()->get();
        foreach ($roles as $role) {
            $user->role = $role->id;
        }
        return view('admin.users.edit')->with(compact('user'));
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
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ],[]);

        $request->offsetSet('password', bcrypt($request->password));
        
        $user = User::find($id);
        $user->update($request->except('password_confirmation'));

        if ($request->role) {
             $user->detachRoles();
             $role =  \App\Role::find($request->role);
             $user->attachRole($role);
        }

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil menyimpan '.$user->name,
            ]
        );

        return redirect()->route('users.index');
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
        $user = User::find($id);
        $user->detachRoles();

        if (!User::destroy($id)) {
            return redirect()->back();
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "User berhasil dihapus"
        ]);
        return redirect()->route('users.index');
    }
}
