<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Processes\UserProcess;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @var UserProcess
     */
    private $userProcess;

    /**
     * CompanyController constructor.
     * @param UserProcess $userProcess
     */
    public function __construct(UserProcess $userProcess)
    {
        $this->userProcess = $userProcess;
    }

    public function getUser()
    {
        return $this->userProcess->getUser();
    }

    public function updateProfileUser(Request $request)
    {
        $data = $request->all();
        return $this->userProcess->updateProfileUser($data);
    }

    public function getUsersTop(Request $request)
    {
       
        return $this->userProcess->getUsersTop($request);
    }
    public function register(Request $request)
    {
        return $this->userProcess->register($request);
    }
    public function getFarmerId($id)
    {
        return $this->userProcess->getFarmerId($id);
    }
    public function logout(){
        return $this->userProcess->logout();
    }

    public function notificationPush(Request $request){
        return $this->userProcess->notificationPush($request);
    }

    public function pushNotification()
	    {

	        $data=[];
	        $data['message']= "Some message";

	        $data['booking_id']="my booking booking_id";
	        
            $tokens = [];
            $tokens[] = 'egJ_ZLzLTzGAk8hKXVEwdE:APA91bF6RVAsKn4v1m3oWfTkewZBY3fz4KTLbPMEQ4Ijk7XRSaaoWyEFMQTR6k5n1qBT9wNIY5JakKmZ9vUHqqBmp71YAxVLgBEb1jylRH_v97QNBQm-JeHOL5qGtgSfTVIQqXgwupbf';
	        $response = $this->sendFirebasePush($tokens,$data);

	    }
        public function sendFirebasePush($tokens, $data)
	    {

	        $serverKey = 'AAAAZ4_ydHs:APA91bFA0Zig8F0keqBl4sUpJs9yIHN2oLLIBGFTjMHIIQa1cisbapLLAMqoSbmB6tQ1X_Eu5mWXWxXRA3J4AxNnFYW9ITWphDJv4NSim0hV4t2GZmejebimE8ZbQeOc61og7cYTHUru';
	        
	        // prep the bundle
	        $msg = array
	        (
	            'message'   => $data['message'],
	            'booking_id' => $data['booking_id'],
	        );

	        $notifyData = [
                 "body" => $data['message'],
                 "title"=> "Port App"
            ];

	        $registrationIds = $tokens;
	        
	        if(count($tokens) > 1){
                $fields = array
                (
                    'registration_ids' => $registrationIds, //  for  multiple users
                    'notification'  => $notifyData,
                    'data'=> $msg,
                    'priority'=> 'high'
                );
            }
            else{
                
                $fields = array
                (
                    'to' => $registrationIds[0], //  for  only one users
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
