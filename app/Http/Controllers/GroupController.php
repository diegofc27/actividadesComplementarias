<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Teacher;
use App\Group;
use App\Activity;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
         //if(!$request->ajax()) return redirect('/');
        
        $search = $request->search;
        $criteria = $request->criteria;
        $query = Teacher::join('groups', 'teachers.id', '=', 'groups.id_teacher')
        ->join('activities', 'groups.id_activity', '=', 'activities.id')
        ->select('groups.id', 'groups.name', 'groups.condition', 'teachers.id as id_teacher', 'teachers.name as teacher_name', 
        'teachers.lastName1 as teacher_lastName1', 'teachers.lastName2 as teacher_lastName2', 'groups.max_students', 'groups.num_students',
        'groups.id_activity', 'activities.name as activity_name', 'groups.id_activity', 'groups.schedule');

        if ($search=='') {

            $groups = $query
            // ->where('groups.condition', '!=', '0', 'AND', 'teachers.condition', '!=', '0')
            ->orderBy('groups.id', 'desc')->paginate(10);

            
        }else {
            $groups = $query
            ->where('groups.'.$criteria, 'like', '%'. $search . '%', 'AND', 'teachers.condition', '!=', '0', 'AND', 'groups.condition', '!=', '0')            
            ->orderBy('groups.id', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total' => $groups->total(),
                'current_page' => $groups->currentPage(),
                'per_page' => $groups->perPage(),
                'last_page' => $groups->lastPage(),
                'from' => $groups->firstItem(),
                'to' => $groups->lastItem(),
            ],
            'id_user' => Auth::user()->id,
            'groups' => $groups
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
        //if(!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $group = new Group();
            $group->name = strtoupper($request->name);
            $group->id_teacher = $request->id_teacher;
            $group->max_students = $request->max_students;
            $group->id_activity = $request->id_activity;
            $group->schedule = strtoupper($request->schedule);
            $group->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            $group = Group::findOrFail($request->id);

            $group->name = strtoupper($request->name);
            $group->id_teacher = $request->id_teacher;
            $group->max_students = $request->max_students;
            $group->id_activity = $request->id_activity;
            $group->condition = '1';
            $group->schedule = strtoupper($request->schedule);
            $group->save();

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

        $group = Group::findOrFail($request->id);
        $group->condition = !$group->condition;
        $group->save();

    }

        /**
     * Select Role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function selectActivity(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $activities = Activity::where('condition','=', '1')
        ->select('id', 'name')
        ->orderBy('name', 'asc')
        ->get();

        return ['activities' => $activities];
    }
    public function selectTeacher(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $teachers = Teacher::where('condition','=', '1')
        ->select('id', 'name', 'lastName1', 'lastName2')
        ->orderBy('name', 'asc')
        ->get();

        return ['teachers' => $teachers];
    }

}