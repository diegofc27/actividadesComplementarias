<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\User;
use App\Group;
use App\Certificate;
use App\Q8;
use Illuminate\Support\Facades\DB;
use PDF;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $search = $request->search;
        $criteria = $request->criteria;
        $period = $request->period;
        $query = User::join('students', 'users.id', '=', 'students.id_user')
        ->join('roles', 'users.id_role', '=', 'roles.id')
        ->join('q8_groups', 'students.id_q8', '=', 'q8_groups.id')
        ->leftjoin('groups', 'groups.id', '=', 'students.id_group')
        ->select('students.id', 'students.name', 'students.paternal_surname as lastName1', 'students.maternal_surname as lastName2', 'students.period', 
        'students.id_group', 'students.condition as students_condition', 'q8_groups.id as id_q8', 'q8_groups.nameq8 as q8Name', 'users.condition', 'users.password', 
        'users.email', 'users.id_role', 'students.id_user', 'groups.id as group_id', 'groups.name as group_name', 'students.grade', 'students.career');


        if ($search=='') {
            $students = $query
            ->where('students.condition', '!=', '0', 'AND', 'students.period', '==', $period)
            ->where('students.period', '=', $period)
            ->orderBy('students.name', 'asc')->paginate(8);
        } else if($criteria == 'email'){
            $students = $query
            ->where('users.'.$criteria, 'like', '%'. $search . '%', 'AND', 'students.condition', '!=', '0', 'AND', 'users.condition', '!=', '0')
            ->where('students.period', '=', $period)
            ->orderBy('students.name', 'asc')->paginate(8);
        } else if($criteria == 'name'){
            $students = $query
            ->where('students.'.$criteria, 'like', '%'. $search . '%', 'AND', 'students.condition', '!=', '0', 'AND', 'users.condition', '!=', '0')
            ->orWhere('students.paternal_surname', 'like', '%'. $search . '%', 'AND', 'students.condition', '!=', '0', 'AND', 'users.condition', '!=', '0')
            ->orWhere('students.maternal_surname', 'like', '%'. $search . '%', 'AND', 'students.condition', '!=', '0', 'AND', 'users.condition', '!=', '0')
            ->where('students.period', '=', $period)
            ->orderBy('students.name', 'asc')->paginate(8);
        }else if ($criteria == 'nameQ8'){
            $students = $query
            ->where('q8_groups.'.$criteria, 'like', '%'. $search . '%', 'AND', 'students.condition', '!=', '0', 'AND', 'users.condition', '!=', '0')
            ->where('students.period', '=', $period)
            ->orderBy('students.name', 'asc')->paginate(8);
        } else {
            $students = $query
            ->where('students.'.$criteria, 'like', '%'. $search . '%', 'AND', 'students.condition', '!=', '0', 'AND', 'users.condition', '!=', '0')
            ->where('students.period', '=', $period)
            ->orderBy('students.name', 'asc')->paginate(8);
        }   

        $periods = DB::table('students')->select('period')->distinct()->orderBy('period','desc')->get();

        return [
            'pagination' => [
                'total' => $students->total(),
                'current_page' => $students->currentPage(),
                'per_page' => $students->perPage(),
                'last_page' => $students->lastPage(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
            ],
            'students' => $students,
            'periods' => $periods
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function export_Certificate(Request $request)
    // {
    //     $check =  Certificate::where('id_student', '=', $request->id_student)->count();
    //     if ($check > 1) {
    //         //El Alumno curso las dos materias y esta certificado
    //         $pdf = PDF::loadView('users.certificate');
    //         return $pdf->download('certificado_Extraescolares.pdf');
    //     } else {
    //         //El Alumno ha cursado 1 o ninguna extraescolar.
    //         $certificated = false;
    //         return response()->json($certificated);
    //     }
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $find = DB::table('users')->where('email', '=', $request->username)->get();

            if ($find->count() == 0) {
                $user = new User();
                $user->email = $request->username;
                $user->password = password_hash($request->password, PASSWORD_BCRYPT);
                $user->id_role = 3; //Student
                $user->save();

                $student = new Student();
                $student->name = strtoupper($request->name);
                $student->paternal_surname = strtoupper($request->paternal_surname);
                $student->maternal_surname = strtoupper($request->maternal_surname);
                $student->period = strtoupper($request->period);
                $student->id_group = $request->id_group;
                $student->id_q8 = $request->id_q8;
                $student->career = $request->career;
                $student->grade = $request->grade;
                $student->id_user = $user->id;
                $student->save();

                if ($student->id_group != null)
                {
                    $group = Group::findOrFail($student->id_group);
                    $group->num_students++;
                    $group->save();
                }
                if ($student->id_q8 != null)
                {
                    DB::table('q8_groups')
                    ->where('q8_groups.id', '=', $student->id_q8)
                    ->increment('num_students', 1);
                }

                DB::commit();   
            }
            else{
                return 'Número de Control ya registrado.';
            }
                

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();
            //Search the user who's about to get modified
            $student = Student::findOrFail($request->id);
            
            $user = User::findOrFail($student->id_user);
            if ($student->id_q8 != $request->id_q8) {
                $group1 = Q8::findOrFail($student->id_q8);
                $group2 = Q8::findOrFail($request->id_q8);
                $group1->num_students--;
                $group2->num_students++;
                $group1->save();
                $group2->save();

            }

            $student->name = strtoupper($request->name);
            $student->paternal_surname = strtoupper($request->paternal_surname);
            $student->maternal_surname = strtoupper($request->maternal_surname);
            $student->period = strtoupper($request->period);
            $student->id_q8 = $request->id_q8;
            $student->id_group = $request->id_group;
            $student->grade = $request->grade;
            $student->save();

            $user->email = $request->username;
            //$user->password = bycrypt($request->password);
            $user->condition = '1';
            $user->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Logic recovery of a User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toggle(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $student = Student::findOrFail($request->id);
        $student->condition = !$student->condition;      
        $student->save();

        $user = User::findOrFail($student->id_user);
        $user->condition = !$user->condition;      
        $user->save();

        if ($student->condition) {
            if ($student->id_group != null)
            {
                $group = Group::findOrFail($student->id_group);
                $group->num_students++;
                $group->save();
            }
            if ($student->id_q8 != null)
            {
                DB::table('q8_groups')
                ->where('q8_groups.id', '=', $student->id_q8)
                ->increment('num_students', 1);
            }
        } else {
            if ($student->id_group != null)
            {
                $group = Group::findOrFail($student->id_group);
                $group->num_students--;
                $group->save();
            }
            if ($student->id_q8 != null)
            {
                DB::table('q8_groups')
                ->where('q8_groups.id', '=', $student->id_q8)
                ->decrement('num_students', 1);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getGroups(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $groups = Group::where('condition','=', '1')
        ->select('id', 'name')
        ->orderBy('name', 'asc')
        ->get();

        return ['groups' => $groups];
    }


    public function setGrade(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $student = Student::findOrFail($request->id);
        $student->grade = $request->grade;
        $student->save();

        DB::commit();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inscribirAlumno(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            //Search the user who's about to get modified

            $student = Student::where('id_user', '=', $request->id_user)->firstOrFail();
            $student->id_group = $request->id_group;
            $student->save();

            $group = Group::findOrFail($request->id_group);
            $group->num_students++;
            $group->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // public function pdf(Request $request)
    // {
    //     return;
    // }

}
