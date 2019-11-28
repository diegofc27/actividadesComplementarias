<?php

namespace App\Http\Controllers;

use App\{Group,Student,User};
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    
    public function acta($id)
    {
    	$student= Student::find($id);
    	$pdf = PDF::loadView('acta_acreditacion',['estudiante' => $student]);
    	return $pdf->stream();
    }

    public function registro($id)
    {
        $students = Student::where("id_group",$id)->get();
        $group = Group::find($id);
        $pdf = PDF::loadView('reporte_participantes',['estudiantes' => $students, 'grupo'=>$group]);
        return $pdf->stream();
    }

}
