<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index() {
        //
        $page = request('page');

        $response = Http::get( 'https://reqres.in/api/users?page=' . $page );

        $users = $response->object();

        return view( 'users.index', compact( 'users' ) );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        //
        return view('users.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store(Request $request) {
        //
        $this->validate( $request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
        ] );

        $response = Http::post('https://reqres.in/api/users', [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ] );

        if ( $response->successful() ) {
            $user = $response->object();
            return redirect()->route( 'users.index' )->with( 'success', 'User ' . $user->id . ' created' );
        }

        return redirect()->back();
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */

    public function show($id) {
        //
        $response = Http::get( 'https://reqres.in/api/users/' . $id );

        $user = $response->object()->data;

        return view( 'users.show', compact( 'user' ) );
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */

    public function edit($id) {
       //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */

    public function update(Request $request,$id) {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */

    public function destroy($id){
        $UserToBeDeleted = Http::get( 'https://reqres.in/api/users/' . $id );
        if($UserToBeDeleted =='{}'){
            return redirect()->back()->with( 'success', ' No User Found' );
        } else{
            $userdeleted = Http::delete( 'https://reqres.in/api/users/' . $id );
            $status = $userdeleted->status();
            if ( $status == 204 ) {
                return redirect()->back()->with( 'success', ' User Deleted' );
            }else{
                return redirect()->back()->with( 'success', 'There was an error please try again' );
            }
        }
    }
}
