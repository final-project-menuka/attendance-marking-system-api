<?php
namespace App\Exceptions;

use App\Exceptions\ExceptionModels;
use Illuminate\Http\Response;

trait ExceptionTrait{
    /**
     * Application Exceptions Handled By This Method
     * 
     */
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
        }elseif($exception->getMessage() === ExceptionModels::PRESENTED_LECTURE){
            return response()->json([
                'MESSAGE'=>"YOU_ARE_IN_THE_LECTURE_HALL_PLEASE_TRY_AGAIN",
                'CODE'=>$exception->getMessage()
            ],409);
        }elseif($exception->getMessage() === ExceptionModels::MAC_ADDRESS_NOT_EXISTS){
            return response()->json([
                'MESSAGE'=>"MAC_ADDRESS_NOT_FOUND",
                'CODE'=>$exception->getMessage()
            ],404);
        }elseif($exception->getMessage() === ExceptionModels::YOU_ARE_NOT_A_VALIED_STUDENT){
            return response()->json([
                'MESSAGE'=>"YOU_ARE_NOT_A_STUDENT_OF_NSBM_STUDENT",
                'CODE'=>$exception->getMessage()
            ],401);
        }
        elseif($exception->getMessage() === ExceptionModels::LEFT_EARLY){
            return response()->json([
                'MESSAGE'=>"STUDENT_LEFT_EARLY",
                'CODE'=>$exception->getMessage()
            ],401);
        }else{
            return response()->json([
                'MESSAGE'=>"SOMETHING_WENT_WRONG",
                'CODE'=>'SOMETHING_WENT_WRONG'
            ],500);
        }
    }
}