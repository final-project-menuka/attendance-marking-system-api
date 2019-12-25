<?php
namespace App\Services;
use App\User;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionModels;
use Illuminate\Support\Str;

/**
 * All Authentication Service Managed By This Class
 */
class AuthService{


    private $user;

    public function __construct(User $user){
        $this->user = new User();
    }

    /**
     * Login Service Manage By This Method
     */
    public function login($request){
        if(!empty($request->input('email')) && !empty($request->input('macAddress'))){
            $login_user = $this->user::where('email',$request->input('email'))->first();
            if($login_user !== null  && $login_user['password'] === hash('sha256',$request->input('password'))){
               return response()->json([$login_user],200);
            }else{
                throw new \Exception(ExceptionModels::Login_Error);
            }
        }else{
            throw new \Exception(ExceptionModels::Login_Error);
        }
    }
}