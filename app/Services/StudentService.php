<?php
namespace App\Services; 

use App\User;
use Illuminate\Support\Str;
use App\Exceptions\ExceptionModels;
use App\OnGoingLec;
use App\StudentAttendance;
use App\Students;

/**
 * All Students Services Manage By This Method
 */
class StudentService
{
    private $user;
    private $on_going_lec;
    private $student_attendence;
    private $student_model;

    public function __construct(User $user,OnGoingLec $on_going_lec,StudentAttendance $student_attendence,Students $student_model) {
        $this->user = $user;
        $this->on_going_lec = $on_going_lec;
        $this->student_attendence = $student_attendence;  
        $this->student_model = $student_model;                  
    }

    /**
     * New Student's Services Done By This Method
     */
    public function new_student($request){
        if(!empty($request->input('id')) && !empty($request->input('imei'))){
            $new_student = $this->student_model::where('nsbm_id',$request->input('id'))->first();
            if(!empty($new_student)){
                $email = $this->user::where('email',$request->input('email'))->first();
                if($email === null || empty($email)){
                    $this->user->id = Str::uuid();
                    $this->user->name = $new_student['name'];
                    $this->user->email = $request->input('email');
                    $this->user->password = hash('sha256',$request->input('password'));
                    $this->user->imei_number = $request->input('imei');
                    $this->user->nsbm_id = $new_student['nsbm_id'];
                    $this->user->batch = $new_student['batch'];
                    $this->user->course = $new_student['course'];
                    $this->user->role = 2;
                    $this->user->save();
                    return response()->json($this->user,200);
                }else{
                    throw new \Exception(ExceptionModels::USER_EXISTS);
                }
            }else{
                throw new \Exception(ExceptionModels::YOU_ARE_NOT_A_VALIED_STUDENT);    
            }
        }else{
            throw new \Exception(ExceptionModels::ID_OR_IMEI_NOT_VALIED);
        }
    }

    /**
     * Attendance Mark Done By This Method
     * 
     */
    public function mark_attendance($request)
    {
        if(!empty($request->input('id')) && !empty($request->input('macAddress'))){
            $on_going_lecture = $this->on_going_lec::where('start_time','<=',date('Y-m-d H:i:s'))
            ->where('end_time','>=',date('Y-m-d H:i:s'))->where('course',$request->input('course'))->where('batch',$request->input('batch'))
            ->where('mac_address',$request->input('macAddress'))->first();
            if(!empty($on_going_lecture)){
                $attendence = $this->student_attendence::where('module_code',$on_going_lecture['module_code'])
                ->where('student_id','=',$request->input('id'))->first();
                if(empty($attendence)){
                    $this->student_attendence->attended_time = date('Y-m-d H:i:s');
                    $this->student_attendence->student_id = $request->input('id');
                    $this->student_attendence->module_code = $on_going_lecture['module_code'];
                    $this->student_attendence->lec_hall_id = $on_going_lecture['lec_hall_id'];
                    $this->student_attendence->lec_hall_num = $on_going_lecture['lec_hall_number'];
                    $this->student_attendence->student_name = $request->input('name');
                    $this->student_attendence->save();
                    return response()->json($this->student_attendence,201);
                }else{
                    throw new \Exception(ExceptionModels::PRESENTED_LECTURE);
                }
            }else{
                throw new \Exception(ExceptionModels::LEC_HALL_NOT_FOUND);
            }
        }else{
            throw new \Exception(ExceptionModels::MAC_ADDRESS_NOT_EXISTS);
        }
    }
}