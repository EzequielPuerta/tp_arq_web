<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Room;
use  App\Models\User;
use Pusher\Pusher;
use Illuminate\Support\Facades\App;


class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mainRoom = Room::getMainRoom();
        
        $user = User::getUserNamed( $request['name']);
        
        $mainRoom->host($user);

        return view('message', ['roomName'=> $mainRoom->name ]);
    }

    

    public function trigger(Request $request)
    {
        $pusher = App::make('pusher');


    //primer parametro nombre del channel, segundo el nombre del evento
        $pusher->trigger('Lobbyprincipal', 'notify-message', 'hola');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
