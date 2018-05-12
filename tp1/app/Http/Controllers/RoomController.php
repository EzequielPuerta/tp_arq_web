<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Room;
use  App\Models\User;
use Pusher\Pusher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class RoomController extends Controller {
    public function index(Request $request) {
        $mainRoom = Room::getMainRoom();
        $user = User::getUserNamed($request['name']);
        $mainRoom->host($user);
        return view('message', [
                'roomName'=> $mainRoom->name,
                'user' => $user ]
        );
    }

    public function sendMessage($user, $message) {
        $pusher = App::make('pusher');

        $pusher->trigger('Lobby_principal', 'client-notify-message', 
            array('message' => $message, 'user' => $user));
    }
    
    public function triggerImage(Request $request)
    {
        $pusher = App::make('pusher');
        
          $user = $request['user'];
          $img = $request['image'];
          $extension = $request['extension'];

         define('UPLOAD_DIR', public_path());
      

            $uniqueIDAndExtension = uniqid().'.'.$extension;
            
            $output_file = UPLOAD_DIR .'\\'.  $uniqueIDAndExtension;
             
             $ifp = fopen( $output_file, 'wb' ); 

            $data = explode( ',', $img );

            // we could add validation here with ensuring count( $data ) > 1
            fwrite( $ifp, base64_decode( $data[ 1 ] ) );

            fclose( $ifp );   

        //primer parametro nombre del channel, segundo el nombre del evento
        $pusher->trigger('Lobby_principal', 'client-notify-image', 
            array('image' => $uniqueIDAndExtension, 'user' => $user));
    }



    public function showAllUsers($thisUserId) {
        $thisUser = User::find($thisUserId);
        return view('allUsers',['users' => User::getAllUsers(), 'thisUser' => $thisUser]);
    }

    public static function openPrivateChat($firstUser, $secondUser) {
        $private = function ($room) { 
            return $room['private'];
        };

        $firstUserPrivateRooms = $firstUser-> rooms()-> get()-> filter($private);

        foreach ($firstUserPrivateRooms as $privateRoom) {
            if ($privateRoom-> users()-> get()-> contains('id', $secondUser->id)) {
                return view('message', [
                    'roomName' => $privateRoom-> name,
                    'user' => $firstUser
                ]);
            }
        }
    
        return RoomController::create($firstUser, [$firstUser, $secondUser], true, 'Sala Privada');
    }

    private static function create($user, $members, $private, $roomName) {
        $newRoom = Room::createRoom($roomName, $members, $private);

        return view('message', [
            'roomName'=> $roomName,
            'user' => $user
        ]);
    }
}
