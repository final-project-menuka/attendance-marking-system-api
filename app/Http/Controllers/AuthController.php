<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Services\UserService;

class AuthController extends Controller
{

    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    /**
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_user(Request $request)
    {
        return $this->userService->new_user($request);
    }

    public function login(Request $request){
        return $this->userService->login($request);
    }
    
}
