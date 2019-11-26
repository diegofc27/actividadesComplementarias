<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Group,Student,User};
use PDF;
class ReportController extends Controller
{
    public function registro($id)
    {
    	$students = Student::where("id_group",$id)->get();
    	$group = Group::find($id);
    	$pdf = PDF::loadView('reporte_participantes',['estudiantes' => $students, 'grupo'=>$group]);
    	return $pdf->stream();
    }

    public function acta($id)
    {
    	$student= Student::find($id);
    	$pdf = PDF::loadView('acta_acreditacion',['estudiante' => $student]);
    	return $pdf->stream();
    }
}
