<?php
namespace App\Services;
use App\User;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionModels;
use Illuminate\Support\Str;
use App\LectureHalls;


class UserService{


    private $user;
    private $lecture_halls;

    public function __construct(){
        $this->user = new User();
        $this->lecture_halls = new LectureHalls();
    }

    public function new_user($request){
        //$user = new User();
        if(!empty($request->input('id')) && !empty($request->input('imei'))){
            $email = $this->user::where('email',$request->input('email'))->first();
            if($email === null || empty($email)){
                $this->user->id = Str::uuid();
                $this->user->name = $request->input('name');
                $this->user->email = $request->input('email');
                $this->user->password = hash('sha256',$request->input('password'));
                $this->user->imei_number = $request->input('imei');
                $this->user->nsbm_id = $request->input('id');
                $this->user->role = 2;
                $this->user->save();
                return response()->json($this->user,200);
            }else{
                $err = new stdClass();
                $err->message = 'user Exists';
                throw new \Exception(ExceptionModels::USER_EXISTS);
            }
        }else{
            throw new \Exception(ExceptionModels::ID_OR_IMEI_NOT_VALIED);
        }
    }

    public function login($request){
        if(!empty($request->input('email')) && !empty($request->input('macAddress'))){
            $login_user = $this->user::where('email',$request->input('email'))->first();
            $lecture_hall = $this->lecture_halls::where('mac_address',$request->input('macAddress'))->first();
            if(!empty($lecture_hall) && $lecture_hall['mac_address'] !== null){
                if($login_user !== null  && $login_user['password'] === hash('sha256',$request->input('password'))){
                    return response()->json([$login_user,$lecture_hall],200);
                }else{
                    throw new \Exception(ExceptionModels::Login_Error);
                }
            }else{
                throw new \Exception(ExceptionModels::LEC_HALL_NOT_FOUND);
            }
        }else{
            throw new \Exception(ExceptionModels::Login_Error);
        }
        return response()->json(['test'=>'test service'],200);
    }
}