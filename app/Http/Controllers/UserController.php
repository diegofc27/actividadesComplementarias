<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Redirect;

use App\User;
use App\Teacher;
use App\Student;
use App\Group;
use App\Role;
use Auth;
use Session;

class UserController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

        //todo Formulario para crear Usuario
        public function userForm()
        {
            $data['roles'] = Role::all();
    
            dd("eaa");

            $actual_user = Auth::user()->email;

            $students = User::join('students', 'users.id', '=', 'students.id_user')
            ->join('roles', 'users.id_role', '=', 'roles.id')
            ->leftjoin('groups', 'groups.id', '=', 'students.id_group')           
            ->rightjoin('teachers', 'teachers.id', 'groups.id_teacher') 
            ->select('students.id', 'students.name', 'students.id_group', 'students.condition as students_condition', 'users.condition', 'users.password', 'users.email' , 
            'users.id_role', 'students.id_user', 'groups.id as group_id', 'groups.name as group_name', 'groups.max_students as max_students', 'groups.schedule as schedule', 'groups.num_students as num_students',
            'students.paternal_surname as paternal_surname', 'students.maternal_surname as maternal_surname', 'students.grade as grade')
            ->where('students.condition', '=', '1')
            ->where('users.condition', '!=', '0')
            ->orderBy('students.id', 'desc')->paginate(8);
    
            $grupos = Auth::user()->join('teachers', 'users.id','=','teachers.id_user')
                            ->join('groups', 'teachers.id','=','groups.id_teacher')
                            ->select('groups.name', 'groups.num_students', 'groups.max_students', 'groups.schedule')
                            ->where('users.email', '=', $actual_user)->get();
    
            //dd($grupos);
            $data['roles'] = Role::all();
        
            return view("users.list_group", $grupos);
        }

        public function altaUsuario()
        {
            $data['roles'] = Role::all();
    
            return view("content.content", $data);
        }
    
        public function registerUser(Request $request)
        {
            dd("registerUSer");
            $user = new User;

            try {
                $usuario = User::where('email','=',$request->username)->first();                         
                //Comienzan las validaciones
                if($request->username == $usuario->email && $request->role == $usuario->id_role)
                {
                    return Redirect::back()->withInput()->withErrors(['El usuario ya se encuentra registrado en el sistema']);
                }
              }
              catch (\Exception $e) {
                  
              }

            

            if($request->password != $request->password_confirm)//Las contraseñas no coinciden
            {
                return Redirect::back()->withInput()->withErrors(['Las contraseñas no coinciden']);
            }


            $user->email        = $request->username;
            $user->password     = bcrypt($request->password);
            $user->id_role      = $request->role;
            $user->condition    = 1;
            $user->created_at   = date('Y-m-d H:i:s');
            $user->updated_at   = date('Y-m-d H:i:s');

            $res = $user->save();

            
            return Redirect::back()->with('success', 'Usuario registrado correctamente');
        }
    
        
        public function bajaUsuario(Request $request)
        {
            //$usuario = User::where('email','=',$request->username)->first();

            
        }

    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $search = $request->search;
        $criteria = $request->criteria;
        $querty1 = User::join('students', 'users.id', '=', 'students.id_user')
        ->join('roles', 'users.id_role', '=', 'roles.id')
        ->leftJoin('groups', 'students.id_group', '=', 'groups.id')
        ->select('users.id', 'users.email as noControl', 'users.condition', 'users.id_role', 'students.name as name', 'students.id as id_student', 
        'roles.name as role', 'users.created_at', 'groups.id as id_group', 'groups.name as group');

        $querty2 = User::join('teachers', 'users.id', '=', 'teachers.id_user')
        ->join('roles', 'users.id_role', '=', 'roles.id')
        ->select('users.id', 'users.email as noControl', 'users.condition', 'users.id_role',
        'teachers.id as id_teacher', 'teachers.name as name', 'roles.name as role', 'users.created_at');

        if ($search=='') {
            $students = $querty1
            ->orderBy('users.id', 'desc')->paginate(100);

            $teachers = $querty2
            ->orderBy('users.id', 'desc')->paginate(100);

        }else if ($criteria == 'name'){
            $students= $querty1
            ->where('students.'.$criteria, 'like', '%'. $search . '%')
            ->orderBy('users.id', 'desc')->paginate(100);

            $teachers= $querty2
            ->where('teachers.'.$criteria, 'like', '%'. $search . '%')
            ->orderBy('users.id', 'desc')->paginate(100);
        }
        else{
            $students= $querty1
            ->where('users.'.$criteria, 'like', '%'. $search . '%')
            ->orderBy('users.id', 'desc')->paginate(100);

            $teachers= $querty2
            ->where('users.'.$criteria, 'like', '%'. $search . '%')
            ->orderBy('users.id', 'desc')->paginate(100);
        }
        return [
            'pagination' => [
                'total' => $students->total() + $teachers->total(),
                'current_page' => $students->currentPage(),
                'per_page' => 10,
                'last_page' => (ceil(($students->total() + $teachers->total()) / 10)),
                'from' => $students->firstItem(),
                'to' => $teachers->lastItem(),
            ],
            'students' => $students,
            'teachers' => $teachers
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
            //Model Instance

            $user = new User();
            $user->email = $request->username;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $user->condition = '1';
            $user->id_role = $request->id_role;
            $user->save();

            if ($request->id_role == 1) //Admin
            {
                
            } elseif ($request->id_role == 2)  { //Teacher
                $teacher = new Teacher();
                $teacher->name = $request->name;
                $teacher->id_user = $user->id;
                $teacher->save();
            } elseif ($request->id_role == 3) { //Student
                
            } else {
                
            }

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
            $user = User::findOrFail($request->id);

            $customer = Customer::findOrFail($user->id);

            $customer->name = $request->name;
            $customer->document_type = $request->document_type;
            $customer->document_number = $request->document_number;
            $customer->address = $request->address;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->save();


            $user->email = $request->username;
            $user->password = bycrypt($request->password);
            $user->condition = '1';
            $user->id_role = $request->id_role;
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
    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condition = '1';      
        $user->save();
    }

    /**
     * Logic Deletion of a User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condition = '0';      
        $user->save();
    }

    /**
     * Select Role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function selectRole()
    {
        // if(!$request->ajax()) return redirect('/');
        $roles = Role::where('condition','=', '1')
        ->select('id', 'name')
        ->orderBy('name', 'asc')
        ->get();

        return ['roles' => $roles];
    }

    public function pValidation(Request $request)
    {
        $id_user = $request->id_user;
        $user = User::findOrFail($id_user);
        $password = $user->password;
        if ($request->currentPassword == '') { //currectPass emty
            return 'Ingrese la contraseña actual';
        } else if($request->newPass == ''){ //newPass empty
            return 'Ingrese la contraseña nueva';
        } elseif ($request->confirmPass == '') { //confrmPass empty
            return 'Confirme la nueva contraseña';
        } else{ //Ninguno vacio
            if (!Hash::check($request->currentPassword, $password)) {
                return 'Contraseña Actual Incorrecta';
            }
            else {
                if ($request->newPass != $request->confirmPass) {
                    return 'La contraseña nueva no coincide con la de confirmación';
                } elseif ($request->newPass == $request->currentPassword) {
                    return 'La contraseña actual en la misma que la contraseña nueva';
                } else {
                   return '1';
                }
            }
        }
    }

    public function changePassword(Request $request)
    {
        $id_user = $request->id_user;
        $user = User::findOrFail($id_user);
        $user->password = bcrypt($request->newPass);
        $user->save();

        DB::commit();
    }

    public function toggle(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $id = $request->id;
        $id_role = $request->id_role;
        if ($id_role == 2) {
            $user = User::findOrFail($id);
            $user->condition = ($user->condition == 1) ? 0:1;
            $user->save();

            $teacher = Teacher::where('id_user', '=', $id)->first();
            $teacher->condition = ($teacher->condition == 1) ? 0:1;    
            $teacher->save();
        }
        else if($id_role == 3){
            $user = User::findOrFail($id);
            $user->condition = ($user->condition == 1) ? 0:1;      
            $user->save();

            $student = Student::where('id_user', '=', $id)->first();
            $student->condition = ($student->condition == 1) ? 0:1;      
            $student->save();

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
    }
}
