<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Services\AuthService;

/**
 * All Authentication Requests Catch By This Controller
 */
class AuthController extends Controller
{

    private $auth_service;

    public function __construct(AuthService $auth_service){
        $this->auth_service = $auth_service;
    }

    /**
     * Student's Login Request Will Come To Here
     * Post Request
     */
    public function login(Request $request){
        return $this->auth_service->login($request);
    }
    
}
