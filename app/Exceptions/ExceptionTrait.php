<?php
namespace App\Exceptions;

use App\Exceptions\ExceptionModels;
use Illuminate\Http\Response;

trait ExceptionTrait{
    public function user_already_exists($request,$exception){
        if($exception->getMessage() === ExceptionModels::USER_EXISTS ){
            return response()->json([
                'MESSAGE'=>"USER_ALREADY_EXISTS",
                'CODE'=>'USER_EXISTS'
            ],500);
        }elseif($exception->getMessage() === ExceptionModels::Login_Error){
            return response()->json([
                'MESSAGE'=>"PLEASE_CHECK_USERNAME_OR_PASSWORD",
                'CODE'=>'LOGIN_ERROR'
            ],401);
        }elseif($exception->getMessage() === ExceptionModels::LEC_HALL_NOT_FOUND){
            return response()->json([
                'MESSAGE'=>"THERE_IS_NO_LECTURE_HALL_WITH_THIS_MAC_ADDRESS",
                'CODE'=>'LEC_HALL_NOT_FOUND'
            ],404);
        }elseif($exception->getMessage() === ExceptionModels::ID_OR_IMEI_NOT_VALIED){
            return response()->json([
                'MESSAGE'=>"PLEASE_CHECK_ID_OR_IMEI",
                'CODE'=>$exception->getMessage()
            ],404);
        }
    }
}