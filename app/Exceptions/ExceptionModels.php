<?php

namespace App\Exceptions;


/**
 * Application Exception Models
 * 
 */
class ExceptionModels 
{
    public const USER_EXISTS = 'User_Exists';
    public const Login_Error = 'Login_Error';
    public const MAC_ADDRESS_NOT_EXISTS = 'MAC_ADDRESS_NOT_EXISTS';
    public const LEC_HALL_NOT_FOUND = 'LEC_HALL_NOT_FOUND';
    public const ID_OR_IMEI_NOT_VALIED ='ID_OR_IMEI_NOT_VALIED';
    public const PRESENTED_LECTURE = 'PRESENTED_LECTURE';
    public const YOU_ARE_NOT_A_VALIED_STUDENT = 'YOU_ARE_NOT_A_VALIED_STUDENT';
    public const LEFT_EARLY = 'LEFT_EARLY';
    public const TODAY_NO_LECTURES = 'TODAY_NO_LECTURES';
}
