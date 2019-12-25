<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentService;

/**
 * Student's Requests Catch By This Controller
 */
class StudentController extends Controller
{

    private $student_service;

    public function __construct(StudentService $student_service){
        $this->student_service = $student_service;
    }

    /**
     * New Student Signup By This Method
     * Post Request
     */
    public function new_student(Request $request)
    {
        //return response()->json(['ok'=>'ok'],200);
        return $this->student_service->new_student($request);
    }

    /**
     * Attendance Mark By This Method
     * Post Request
     */
    public function mark_attendence(Request $request){
        return $this->student_service->mark_attendance($request);
    }
}
