<?php

namespace App\Repositories;

use App\Models\Image;
use App\Models\ImageParameter;
use App\Models\User;
use App\Models\WalletsByUsers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\LevelRepository;

class UserRepository
{
    public function findById($id)
    {
        return User::query()->find($id);
    }

    public function getUser($email)
    {
        $user = User::query()
            ->where('email', $email)
            ->first();

        return $user ?? null;
    }
    public function register($input)
    {
       
       $user = new User();
       $user->name =  $input["name"];
       $user->identification_card =  $input["identification_card"];
       $user->phone_number =  $input["phone_number"];
       $user->is_association =  $input["is_association"] ?? "NO";
       if (isset($input['password'])) {
        $user->password = bcrypt($input['password']);
        }   
        $user->save();
        if (isset($input['role'])) {
        $user->syncRoles($input['role']);
        }
    }

    public function getFarmers(){
        $farmers = User::whereHas('roles', function ($q) {
            $q->where('roles.name', '=', 'Vendedora');
          })->get();

    return $farmers ?? null;

    }
    public function getFarmerId($id)
    {
        $user = User::query()->find($id);
        return $user ?? null;
    }
    public function getImageParameter()
    {
        $imageParameter = ImageParameter::query()
            ->where('entity', ImageParameter::TYPE_USER)
            ->where('name', 'Perfil')->first();
      
        return $imageParameter->id;
    }

    public function getImageModel($imageParameterId)
    {

        $imageModel = Image::query()
            ->where('entity_id', Auth::user()->id)
            ->where('image_parameter_id', $imageParameterId)->first();

        return $imageModel ?? null;
    }
    public function notificationPush($input){
        $count = 0;
        $data=[];
        $data['message']= $input['message'];
        $title = $input['title'];
        $tokens = [];
        if(!isset($input['token'])){
            $sellers = $this->getFarmers();
            foreach ($sellers as $seller) {
                $tokens[$count] = $seller->device_token;
                $count ++;
            }       
        }
        else{
            $tokens[] = $input['token'];
       }

        $response = $this->sendFirebasePush($tokens,$data, $title);
      
    }
    public function sendFirebasePush($tokens, $data, $title)
    {

        $serverKey = config('constants.firebasekey');
            $msg = array
        (
            'message'   => $data['message'],
        );

        $notifyData = [
             "body" => $data['message'],
             "title"=> $title
        ];

        $registrationIds = $tokens;
        
        if(count($tokens) > 1){
            $fields = array
            (
                'registration_ids' => $registrationIds, //  para multiples usuarios
                'notification'  => $notifyData,
                'data'=> $msg,
                'priority'=> 'high'
            );
        }
        else{
            
            $fields = array
            (
                'to' => $registrationIds[0], //  para un usuario
                'notification'  => $notifyData,
                'data'=> $msg,
                'priority'=> 'high'
            );
        }
            
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        // curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        if ($result === FALSE) 
        {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close( $ch );
        return $result;
    }
}
