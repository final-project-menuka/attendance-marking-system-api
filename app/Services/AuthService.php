<?php
namespace App\Services;
use App\User;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionModels;
use Illuminate\Support\Str;
use App\util\AccessTokenFactory;//use stdClass;


/**
 * All Authentication Service Managed By This Class
 */
class AuthService{


    private $user;
    private $access_token_factory;

    public function __construct(User $user){
        $this->user = new User();
        $this->access_token_factory = new AccessTokenFactory();
    }

    /**
     * Login Service Manage By This Method
     */
    public function login($request){
        if(!empty($request->input('email')) && !empty($request->input('imei'))){
            $login_user = $this->user::where('email',$request->input('email'))->first();
            if($login_user !== null  && $login_user['password'] === hash('sha256',$request->input('password')) && $request->input('imei') === $login_user['imei_number']){
                $payload = new \stdClass();
                $payload->tokenId = Str::uuid();
                $payload->userId = $login_user['id'];
                $payload->role = $login_user['role'];
                $payload->nsbmId = $login_user['nsbm_id'];
                $cookies = $this->access_token_factory->generate_access_token($payload);
                $response = new Response($login_user);
                $response->withCookie('att_sys_payload',$cookies['signature'],3600);
                $response->withCookie('att_sys_sig',$cookies['payload'],3600);
                return $response;
            }else{
                throw new \Exception(ExceptionModels::Login_Error);
            }
        }else{
            throw new \Exception(ExceptionModels::Login_Error);
        }
    }
}
