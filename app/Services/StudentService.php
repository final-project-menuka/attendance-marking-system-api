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
                ->where('student_id','=',$request->input('id'))->where('attended_time','>=',$on_going_lecture['start_time'])->where('attended_time','<=',$on_going_lecture['end_time'])->first();
                if(empty($attendence)){
                    $this->student_attendence->attended_time = date('Y-m-d H:i:s');
                    $this->student_attendence->student_id = $request->input('id');
                    $this->student_attendence->module_code = $on_going_lecture['module_code'];
                    $this->student_attendence->lec_hall_id = $on_going_lecture['lec_hall_id'];
                    $this->student_attendence->lec_hall_num = $on_going_lecture['lec_hall_number'];
                    $this->student_attendence->student_name = $request->input('name');
                    $this->student_attendence->mac_address = $on_going_lecture['mac_address'];
                    $this->student_attendence->date = $on_going_lecture['date'];
                    date('Y-m-d H:i:s',strtotime('+30 minutes',strtotime($on_going_lecture['start_time']))) > date('Y-m-d H:i:s') ? $this->student_attendence->late = 0:$this->student_attendence->late = 1;
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
    /**
     * check student available at lecture
     */
    public function check_student_in_lecture($request)
    {
        if(!empty($request->input('id')) && !empty($request->input('macAddress')))
        {
            // $lecture = $this->student_attendence->join('on_going_lecs','student_attendances.lec_hall_num','on_going_lecs.lec_hall_number')
            // ->where('student_attendances.mac_address','=',$request->input('macAddress'))->where('student_id','=',$request->input('id'))
            // ->where('attended_time','<',date('Y-m_d H:i:s'))->first();
            // $lecture = $this->student_attendence::where('attended_time','<',function($q){
            //     $q->select('end_time')->from('on_going_lecs')->where('mac_address',$request->input('macAddress'));
            // })->first();
            $lecture = \DB::select("select * from student_attendances where attended_time < (select end_time from on_going_lecs where start_time <='".date('Y-m-d H:i:s')."' and mac_address = '".$request->input('macAddress')."' and end_time >= '".date('Y-m-d H:i:s')."') and student_id='".$request->input('id')."' and module_code = '".$request->input('moduleCode')."'");
            if(!empty($lecture)){
                return response()->json($lecture,200);
            }else{
                $this->student_attendence::where('student_id',$request->input('id'))->where('module_code',$request->input('moduleCode'))
                ->where('attended_time','<',date('Y-m-d H:i:s'))->update(['half_leave'=>1]);
                throw new \Exception(ExceptionModels::LEFT_EARLY);
            }
        }
    }

    public function update_attendance_status($request)
    {
        if(!empty($request->input('macAddress')) && !empty($request->input('id')) && !empty($request->input('moduleCode'))){
            
            return response()->json($this->student_attendence::where('student_id',$request->input('id'))->where('mac_address',$request->input('macAddress'))
            ->where('module_code',$request->input('moduleCode'))->where('date',date('Y-m-d'))->update(['half_leave'=>0]),200);
        }else{
            throw new \Exception(ExceptionModels::LEC_HALL_NOT_FOUND);
        }
    }

    public function get_time_table()
    {
        //response()->json('ok',200);
        $today_lecs = $this->on_going_lec::where('date',date('Y-m-d'))->get();
        if(sizeof($today_lecs) > 0){
            return response()->json($today_lecs,200);
        }
        else{
            throw new \Exception(ExceptionModels::TODAY_NO_LECTURES);
        }
    }
}
