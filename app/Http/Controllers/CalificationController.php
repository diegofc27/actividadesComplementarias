<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\User;
use App\Group;
use App\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Role;
use Illuminate\Support\Facades\Redirect;
use PDF;

class CalificationController extends Controller
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
        $email_maestro = $request->email_maestro;

        $cero = $request->cero;

        $id_teacher = User::join('teachers', 'teachers.id_user', '=', 'users.id')
                             ->select('teachers.id')
                             ->where('users.email', '=', $criteria)->first();
                             //$id_teacher = 2;


        if ($search=='') {

            $students = User::join('students', 'users.id', '=', 'students.id_user')
            ->join('roles', 'users.id_role', '=', 'roles.id')
            ->leftjoin('groups', 'groups.id', '=', 'students.id_group')           
            ->rightjoin('teachers', 'teachers.id', 'groups.id_teacher') 
            ->select('students.id', 'students.name', 'students.id_group', 'students.condition as students_condition', 'users.condition', 'users.password', 'users.email' , 
            'users.id_role', 'students.id_user', 'groups.id as group_id', 'groups.name as group_name', 'groups.max_students as max_students', 'groups.schedule as schedule', 'groups.num_students as num_students',
            'students.paternal_surname as paternal_surname', 'students.maternal_surname as maternal_surname', 'students.grade as grade')
            ->where('groups.id_teacher', '=', $id_teacher->id)
            ->where('students.condition', '=', '1')
            ->where('users.condition', '!=', '0')
            ->orderBy('students.id', 'desc')->paginate(8);

            
        } else {
            $students = User::join('students', 'users.id', '=', 'students.id_user')
            ->join('roles', 'users.id_role', '=', 'roles.id')
            ->join('groups', 'groups.id', '=', 'students.id_group')
            ->select('students.id', 'students.name', 'students.id_group', 'students.condition as students_condition', 'users.condition','users.password', 'users.email' , 
            'users.id_role', 'students.id_user', 'groups.id as group_id', 'groups.name as group_name')
            ->where('students.'.$criteria, 'like', '%'. $search . '%', 'AND', 'users.condition', '!=', '0', 'AND', 'students.condition', '!=', '0')
            ->orderBy('teachers.id', 'desc')->paginate(8);
        }
        
        return [
            'pagination' => [
                'total' => $students->total(),
                'current_page' => $students->currentPage(),
                'per_page' => $students->perPage(),
                'last_page' => $students->lastPage(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
            ],
            'students' => $students
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            $student = new Student();
            $student->grade = $request->grade;

            $student->save();

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

            $student->grade = $request->grade;
            $student->save();


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


    public function GruposEstudiantes(){
        return view('content.content');
    }

    public function group_list(){//*Regresa lista de grupos

        $actual_user = Auth::user()->email;

        $data['grupos'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                        ->join('groups', 'teachers.id','=','groups.id_teacher')
                        ->select('groups.id', 'groups.name', 'groups.num_students', 'groups.max_students', 'groups.schedule')
                        ->where('users.email', '=', $actual_user)->get();

                        //dd($data['grupos']);
    
        return view("users.group_list", $data);
    }

    public function group_students(Request $request){        

        $data['estudiantes'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                           ->join('groups', 'teachers.id','=','groups.id_teacher')
                                           ->join('students', 'students.id_group','=','groups.id')
                                           ->select('students.id' ,'students.name as nombre_estudiante', 'students.paternal_surname', 'students.maternal_surname', 'groups.name as nombre_grupo', 'students.grade')
                                           ->where('groups.id', '=', $request->id)->get();

        $data['grupo'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                     ->join('groups', 'teachers.id','=','groups.id_teacher')
                                     ->join('students', 'students.id_group','=','groups.id')
                                     ->select('groups.name')
                                     ->where('groups.id', '=', $request->id)->first();

            $data['id_grupo'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                     ->join('groups', 'teachers.id','=','groups.id_teacher')
                                     ->join('students', 'students.id_group','=','groups.id')
                                     ->select('groups.id')
                                     ->where('groups.id', '=', $request->id)->first();
        //dd($data['grupo']);
        if($data['grupo'] == null){
            return redirect(route("group_list", ['id' => $request->id]))->withInput()->with('error', 'No hay alumnos inscritos a la materia seleccionada, favor de intentar otra');
        }
        //dd($request->all());

        return view("users.group_students", $data);
    }

    public function show_form_calif(Request $request){
        //dd($request->id);
        $data['alumno'] = Student::find($request->id)->first();

        $data['alumno'] = Student::join('groups', 'students.id_group','=','groups.id')
                                 ->select('students.grade' ,'students.id' ,'students.id_group', 'students.name as nom_alumno', 'students.paternal_surname', 'students.maternal_surname', 'groups.name')
                                 ->where('students.id','=',$request->id)->first();

        return view('users.form_calif', $data);
    }

    public function register_calif(Request $request){

        $estudiante = Student::find($request->id);

        if($estudiante->grade == "N-A" || $estudiante->grade == "AC"){
            return redirect()->back()->with('alert', 'Calificacion ya almacenada!');
        }
        if($request->calif == null){
            return redirect()->back()->with('alert', 'Favor de seleccionar una calificación!');
        }

        //dd($estudiante);

        $estudiante->grade = $request->calif;

        $res = $estudiante->save();





        return redirect()->back()->with('success', 'Calificacion almacenada!');
    }
    
    public function export_pdf(Request $request)
  {
    // Fetch all customers from database
    //$data = Customer::get();
        $data['estudiantes'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                           ->join('groups', 'teachers.id','=','groups.id_teacher')
                                           ->join('students', 'students.id_group','=','groups.id')
                                           ->select('students.id' ,'students.name as nombre_estudiante', 'students.paternal_surname', 'students.maternal_surname', 'groups.name as nombre_grupo', 'students.grade', 'users.email as no_control', 'students.career')
                                           ->where('groups.id', '=', $request->id)->get();
        $data['grupo'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                     ->join('groups', 'teachers.id','=','groups.id_teacher')
                                     ->join('students', 'students.id_group','=','groups.id')
                                     ->select('groups.name')
                                     ->where('groups.id', '=', $request->id)->first();
            $data['id_grupo'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                     ->join('groups', 'teachers.id','=','groups.id_teacher')
                                     ->join('students', 'students.id_group','=','groups.id')
                                     ->select('groups.id')
                                     ->where('groups.id', '=', $request->id)->first();
                                     $data['schedule'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                     ->join('groups', 'teachers.id','=','groups.id_teacher')
                                     ->join('students', 'students.id_group','=','groups.id')
                                     ->select('groups.schedule', 'teachers.name as teacher_name')
                                     ->where('groups.id', '=', $request->id)->first();

    // Send data to the view using loadView function of PDF facade
    $pdf = PDF::loadView('users.pdf_students', $data);
    // If you want to store the generated pdf to the server then you can use the store function
    $pdf->save(storage_path().'_filename.pdf');
    // Finally, you can download the file using download function
    return $pdf->download('grupos.pdf');
  }

  public function show_pdf(Request $request){
              $data['estudiantes'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                           ->join('groups', 'teachers.id','=','groups.id_teacher')
                                           ->join('students', 'students.id_group','=','groups.id')
                                           ->select('students.id' ,'students.name as nombre_estudiante', 'students.paternal_surname', 'students.maternal_surname', 'groups.name as nombre_grupo', 'students.grade', 'users.email as no_control', 'students.career')
                                           ->where('groups.id', '=', $request->id)->get();
        $data['grupo'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                     ->join('groups', 'teachers.id','=','groups.id_teacher')
                                     ->join('students', 'students.id_group','=','groups.id')
                                     ->select('groups.name')
                                     ->where('groups.id', '=', $request->id)->first();
            $data['id_grupo'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                     ->join('groups', 'teachers.id','=','groups.id_teacher')
                                     ->join('students', 'students.id_group','=','groups.id')
                                     ->select('groups.id')
                                     ->where('groups.id', '=', $request->id)->first();
$data['schedule'] = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                                     ->join('groups', 'teachers.id','=','groups.id_teacher')
                                     ->join('students', 'students.id_group','=','groups.id')
                                     ->select('groups.schedule', 'teachers.name as teacher_name')
                                     ->where('groups.id', '=', $request->id)->first();

                                     return view('users.pdf_students', $data);
  }
}
