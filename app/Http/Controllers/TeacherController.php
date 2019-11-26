<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Group;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        
        $search = $request->search;
        $criteria = $request->criteria;
        $period = $request->period;
        $query = User::join('teachers', 'users.id', '=', 'teachers.id_user')
        ->join('roles', 'users.id_role', '=', 'roles.id')
        ->select('teachers.id', 'teachers.name', 'teachers.lastName1', 'teachers.lastName2', 'teachers.condition as teacher_condition',
        'teachers.id_user', 'teachers.period', 'users.condition', 'users.email' , 'users.id_role');

        if ($search=='') {

            $teachers = $query
            ->where('teachers.condition', '!=', '0', 'AND', 'users.condition', '!=', '0')
            ->where('teachers.period', '=', $period)
            ->orderBy('teachers.id', 'desc')->paginate(8);

        }else if($criteria == 'email'){
            $teachers = $query
            ->where('users.'.$criteria, 'like', '%'. $search . '%', 'AND', 'users.condition', '!=', '0', 'AND', 'teachers.condition', '!=', '0')
            ->where('teachers.period', '=', $period)
            ->orderBy('teachers.id', 'desc')->paginate(8);
        }else{
            $teachers = $query
            ->where('teachers.'.$criteria, 'like', '%'. $search . '%', 'AND', 'users.condition', '!=', '0', 'AND', 'teachers.condition', '!=', '0')
            ->where('teachers.period', '=', $period)
            ->orderBy('teachers.id', 'desc')->paginate(8);
        }
        $groupsByTeacher=Group::join('teachers', 'groups.id_teacher', '=', 'teachers.id')
        ->select('teachers.id as id_teacher', 'teachers.name as name_teacher', 'groups.id as id_group', 'groups.name as name_group')
        // ->where('groups.id_teacher', '=', 'teachers.id')
        ->where('teachers.period', '=', $period)
        ->orderBy('teachers.id', 'asc')
        ->get();
        
        $periods = DB::table('teachers')->select('period')->distinct()->orderBy('period','desc')->get();

        return [
            'pagination' => [
                'total' => $teachers->total(),
                'current_page' => $teachers->currentPage(),
                'per_page' => $teachers->perPage(),
                'last_page' => $teachers->lastPage(),
                'from' => $teachers->firstItem(),
                'to' => $teachers->lastItem(),
            ],
            'teachers' => $teachers,
            'groupsByTeacher' => $groupsByTeacher,
            'periods' => $periods
        ];
    }

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

            $user = new User();
            $user->email = strtoupper($request->username);
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $user->id_role = 2; //Teacher
            $user->save();

            $teacher = new Teacher();
            $teacher->id_user = $user->id;
            $teacher->name = strtoupper($request->name);
            $teacher->lastName1 = strtoupper($request->lastName1) ?? 'Sin Apellido';
            $teacher->lastName2 = strtoupper($request->lastName2);
            $teacher->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');


        try {
            DB::beginTransaction();
            //Search the user who's about to get modified
            $teacher = Teacher::findOrFail($request->id);

            $user = User::findOrFail($teacher->id_user);

            $teacher->name = strtoupper($request->name);
            $teacher->lastName1 = strtoupper($request->lastName1) ?? 'Sin Apellido';
            $teacher->lastName2 = strtoupper($request->lastName2);
            $teacher->save();

            $user->email = strtoupper($request->username);
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

        $teacher = Teacher::findOrFail($request->id);
        $teacher->condition = !$teacher->condition;      
        $teacher->save();

        $user = User::findOrFail($teacher->id_user);
        $user->condition = !$user->condition;      
        $user->save();
    }
}
