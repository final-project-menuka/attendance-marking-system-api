<?php
namespace App\util;

class AccessTokenFactory{

    private $secret = 'a0fd5515-ca53-41f7-a9ed-bc774a1c7728';

    
    public function generate_access_token($access_token)
    {
        
        $payload = base64_encode(json_encode($access_token));
        //array('payload'=>$access_token,'signature'=>hash('sha256',$this->secret.$payload));
        return ['payload'=>$payload,'signature'=>hash('sha256',$this->secret.$payload)];
    }

    public function claim_access_token($signature , $payload)
    {
        //$secret = 'a0fd5515-ca53-41f7-a9ed-bc774a1c7728';
        if(hash('sha256',$this->secret.$payload) === $signature){
            return json_decode(base64_decode($payload));
        }else
            null;
    }

}