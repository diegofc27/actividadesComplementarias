<?php
   
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Student;
use App\Group;
use App\Q8;
use App\User;
use Illuminate\Support\Facades\Redirect;

class ExcelController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExportView()
    {
        return view('import');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() 
    {
        foreach (Excel::toArray(new StudentsImport,request()->file('file')) as $key => $value) 
        {
            foreach ($value as $k => $v) {
                if (!empty($v['nombre_alumno'])) {

                    $apellido_paterno = $v['apellido_paterno'];
                    $apellido_materno = $v['apellido_materno'];
                    $numero_control =  $v['no_de_control'];

                    $group = Q8::where('nameQ8','=', $v['grupo'])->first();
                    $user = User::select('id')->whereEmail($v['no_de_control'])->first();

                    if ($group == null) 
                    {
                        //Crear Grupo
                        $newGroup = new Q8();
                        $newGroup->nameQ8 = $v['grupo'];
                        $newGroup->period = $v['periodo'];
                        $newGroup->num_students = 1;
                        $newGroup->save();
                    }else {
                        $group->num_students++;
                        $group->save();
                    }



                    if ($user == null) 
                    {
                        $newUser = new User();
                        $newUser->email = $v['no_de_control'];
                        $newUser->password = bcrypt(
                            //Aqui tomamos la primer letra del paterno, la primera del materno y los ultimos cuatro digitos de el numero de control.
                            substr($apellido_paterno, 0, 1) . substr($apellido_materno, 0, 1) . substr($numero_control, 4, 4)
                        );
                        $newUser->id_role = 3;
                        $newUser->save();
                    }

                    $id_q8 = $group->id ?? $newGroup->id;
                    $id_user = $user->id ?? $newUser->id;

                    $insert[] = 
                    [
                        'career'=> $v['nombre_reducido'],
                        'id_user' => $id_user,
                        'period' => $v['periodo'],
                        'paternal_surname' => $v['apellido_paterno'],
                        'maternal_surname' => $v['apellido_materno'],
                        'name' => $v['nombre_alumno'],
                        'id_q8' => $id_q8,
                        'grade' => 'N/A'//$v['calificacion'],
                    ];

                    if(!empty($insert))
                        Student::insert($insert);
                    $insert = [];
                }
            }
        }
        return Redirect::route('home')->with('success', 'Estudiantes importados satisfactoriamente!');
    }

}