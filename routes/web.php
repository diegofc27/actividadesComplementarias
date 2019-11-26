<?php

use App\Certificate;
use Illuminate\Http\Request;
use App\Student;
use App\Group;
use App\Q8;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('content/content');
}); */

Route::get('/', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::post('/registrar', 'UserController@registerUser')->name('registrar');
Route::get('/admin', 'UserController@altaUsuario')->name('home');
Route::get('/form', 'UserController@userForm');
Route::prefix('admin')->group(function (){

// // Categories
//     //Categories Index
//     Route::get('/categoria', 'CategoryController@index');
//     //Register Category
//     Route::post('/categoria/registrar', 'CategoryController@store');
//     //Update Category
//     Route::put('/categoria/actualizar', 'CategoryController@update');
//     //Deactivate Category
//     Route::put('/categoria/desactivar', 'CategoryController@deactivate');
//     //Activate Category
//     Route::put('/categoria/activar', 'CategoryController@activate');
//     //Categories Select
//     Route::get('/categoria/selectCategoria', 'CategoryController@selectCategory');
// //

// // Items
//     //Categories Index
//     Route::get('/articulo', 'ItemController@index');
//     //Register Category
//     Route::post('/articulo/registrar', 'ItemController@store');
//     //Update Category
//     Route::put('/articulo/actualizar', 'ItemController@update');
//     //Deactivate Category
//     Route::put('/articulo/desactivar', 'ItemController@deactivate');
//     //Activate Category
//     Route::put('/articulo/activar', 'ItemController@activate');
// //

// Customers
    //Customers Index
   // Route::get('/cliente', 'CustomerController@index');
    //Register Customer
   // Route::post('/cliente/registrar', 'CustomerController@store');
    //Update Customer
   // Route::put('/cliente/actualizar', 'CustomerController@update');
//

// // Providers
//     //Providers Index
//     Route::get('/proveedor', 'ProviderController@index');
//     //Register Provider
//     Route::post('/proveedor/registrar', 'ProviderController@store');
//     //Update Provider
//     Route::put('/proveedor/actualizar', 'ProviderController@update');
// //

// Roles
    //Roles Index
    Route::get('/rol', 'RoleController@index');
//

// Users
    //Users Index
    Route::get('/usuario', 'UserController@index');
    //Register User
    Route::post('/usuario/registrar', 'UserController@store');
    //Update User
    Route::put('/usuario/actualizar', 'UserController@update');
    //Deactivate User
    Route::put('/usuario/desactivar', 'UserController@deactivate');
    //Activate User
    Route::put('/usuario/activar', 'UserController@activate');

    Route::post('/usuario/chgPs', 'UserController@changePassword');

    Route::post('/usuario/pVal', 'UserController@pValidation');

    Route::post('/usuario/toggle', 'UserController@toggle');
//

//  Teachers
    Route::get('/maestro', 'TeacherController@index');

    Route::post('/maestro/registrar', 'TeacherController@store');

    Route::post('/maestro/toggle', 'TeacherController@toggle');

    Route::put('/maestro/actualizar', 'TeacherController@update');
//
//  Students
    Route::get('/alumno', 'StudentController@index');
    Route::post('/alumno/registrar', 'StudentController@store');
    Route::post('/alumno/groups', 'StudentController@getGroups');
    Route::post('/alumno/toggle', 'StudentController@toggle');
    Route::put('/alumno/actualizar', 'StudentController@update');
    Route::post('/alumno/setGrade', 'StudentController@setGrade');
    Route::post('/alumno/pdf', 'StudentController@pdf');

    //Certificate
    // Route::post('/alumno/certificacion', 'StudentController@check_Certificate');

    Route::get('/alumno/certificado/{id}', function($id){
        $check =  Certificate::where('id_student', '=', $id)->count();
        if ($check > 1) {
            //El Alumno curso las dos materias y esta certificado
            $data = Student::join('users', 'users.id', '=', 'students.id_user')
            ->select('students.name as student_name', 'students.paternal_surname as lastname1', 'students.maternal_surname as lastname2', 
            'users.email as control_no', 'students.period', 'students.career')
            ->where('students.id', '=', $id)->get();
            $pdf = PDF::loadView('users.certificate', compact('data'));
            return $pdf->download('acta_certificacion.pdf');
        } else {
            //El Alumno ha cursado 1 o ninguna extraescolar.
            $certificated = 'Alumno No Acreditado';
            return response()->json($certificated);
        }
    });
//

//  Selects
    Route::get('/selectRole', 'UserController@selectRole');
    Route::get('/getGroups', 'StudentController@getGroups');
    Route::get('/grupo/selectActivity', 'GroupController@selectActivity');
    Route::get('/grupo/selectTeacher', 'GroupController@selectTeacher');
    Route::get('/getQ8Groups', 'Q8Controller@getQ8');
//
    
//  Groups
    Route::get('/grupo', 'GroupController@index');
    Route::post('/grupo/registrar', 'GroupController@store');
    Route::post('/grupo/actualizar', 'GroupController@update');
    Route::put('/grupo/toggle', 'GroupController@toggle');

    //Q8  Groups
    Route::get('/q8', 'Q8Controller@index');
    Route::post('/q8/registrar', 'Q8Controller@store');
    Route::post('/q8/actualizar', 'Q8Controller@update');
        Route::put('/q8/toggle', 'Q8Controller@toggle');
    // Route::post('/q8/pdf', 'Q8Controller@pdf');

    Route::get('/q8/pdf/{id}', function($id){
        $data = Student::join('q8_groups', 'q8_groups.id','=','students.id_q8')
        ->join('groups', 'groups.id','=','students.id_group')
        ->select('students.name as student_name', 'students.paternal_surname as lastName1', 'students.maternal_surname as lastName2', 
        'students.grade', 'groups.name as group_name', 'q8_groups.nameQ8', 'q8_groups.period')
        ->where('q8_groups.id', '=', $id)->where('students.condition', '!=', '0')->get();

        if ($data->count() > 0){
            $pdf = PDF::loadView('pdf', compact('data'));
            return $pdf->download('AlumnosDeGrupoQ8-'.$data[0]->nameQ8.'-'.$data[0]->period.'.pdf');
        }
        else{
            return 'No hay en este grupo';
        }

    });

    //CalificationsController
    Route::post('/lista_grupos', 'CalificationController@index');
    // Route::get('/grupos_estudiantes', 'CalificationController@GruposEstudiantes');
    Route::post('/grupos_estudiantes/registrar', 'CalificationController@update');

    //Grupo de Alumnos
    // Route::get('/grupo_alumnos/{id}', 'CalificationController');




});

Route::get('/group_list', 'CalificationController@group_list')->name('group_list');
Route::get('/estudiantes/{id}', 'CalificationController@group_students')->name('group_students');
Route::get('/save-calification/{id}', 'CalificationController@show_form_calif')->name('form_calif');
Route::post('/registra_calificacion', 'CalificationController@register_calif')->name('register_calif');
Route::get('/customers/pdf/{id}','CalificationController@export_pdf');

Route::get('show_pdf/{id}', 'CalificationController@show_pdf');
//


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home'); 

//  Activity
    Route::get('/actividad', 'ActivityController@index');
    Route::post('admin/actividad/registrar', 'ActivityController@store');
//

//Excel Import
    Route::get('export', 'ExcelController@export')->name('export');
    Route::get('importExportView', 'ExcelController@importExportView');
    Route::post('import', 'ExcelController@import')->name('import');
//

Route::post('/alumno/inscribir', 'StudentController@inscribirAlumno');

Route::get('/pdf/view', function() {
$pdf = PDF::loadView('acta_acreditacion');
//return $pdf->download('welcome.pdf');
//return $pdf->stream();
//return view("acta_acreditacion");

});

Route::get('registro/{id}','ReportController@registro');
Route::get('acta/{id}','ReportController@acta');