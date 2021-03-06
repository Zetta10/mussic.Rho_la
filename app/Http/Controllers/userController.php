<?php

namespace App\Http\Controllers;
use App\http\requests\validatorUserRequest;
use App\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = DB::table('users')
            ->select('*')
            ->where('users.status', 'activo')
            ->where('first_name', 'LIKE', "%$request->search%")
            ->get();
        //$users = user::get();
        return view('user.index', ['items' => $users]
    );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidatorUserRequest $request)
    {
        $user = user::create(
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $request->role,
                'status' => 'activo',
            ]
        );
        $user->save();
        return redirect( route('user.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = user::findOrFail($id);
        return view('user.show', ['item' => $user]
    );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        if(empty($user)){
            return redirect()->back();
        }
        return view('user.edit')->with('item', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValidatorUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        if(empty($user)){
            return redirect()->back();
        }

        $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $request->role,
                'status' => 'activo',
        ]);
        $user->save();
        return redirect(route('user.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if(empty($id)){
            return redirect()->back();
        }
        $user->update(
            [
                'status' => 'inactivo'
            ]
        );
        return redirect(route('user.index'));
    }
}
