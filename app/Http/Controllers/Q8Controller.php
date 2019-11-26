<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Q8;
use App\Student;
use Illuminate\Support\Facades\DB;
use config\dompdf;
use PDF;

class Q8Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $criteria = $request->criteria;
        $periodS = $request->period;
        $period = date('Y').(date('n') <= 6 ? '1':'2');
    
        if ($search=='') {
            $q8 = DB::table('q8_groups')
            ->select('id', 'nameQ8', 'num_students', 'condition')
            ->where('condition', '!=', '0')
            ->where('period', '=', $periodS)
            ->orderBy('nameQ8', 'asc')->paginate(8);
            
        } else if($criteria == 'nameQ8'){
            $q8 = DB::table('q8_groups')
            ->select('id', 'nameQ8', 'num_students', 'condition')
            ->where($criteria, 'like', '%'. $search . '%', 'AND','condition', '==', 1)
            ->where('period', '=', $periodS)
            ->orderBy('nameQ8', 'asc')->paginate(8);
        }
        else{
            $q8 = DB::table('q8_groups')
            ->select('id', 'nameQ8', 'num_students', 'condition')
            ->where('condition', '!=', $search)
            ->where('period', '=', $periodS)
            ->orderBy('nameQ8', 'asc')->paginate(8);
        }

        $periods = DB::table('q8_groups')->select('period')->distinct()->orderBy('period','desc')->get();
        
        return [
            'pagination' => [
                'total' => $q8->total(),
                'current_page' => $q8->currentPage(),
                'per_page' => $q8->perPage(),
                'last_page' => $q8->lastPage(),
                'from' => $q8->firstItem(),
                'to' => $q8->lastItem(),
            ],
            'q8Groups' => $q8,
            'periods' => $periods
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
        $q8 = new Q8();
        $q8->nameQ8 = strtoupper($request->name);
        $q8->period = strtoupper($request->period);
        $q8->save();

        DB::commit();
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
        $q8 = Q8::findOrFail($request->id);
        $q8->nameQ8 = $request->name;
        $q8->period = $request->period;
        $q8->save();

        DB::commit();
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

    public function getQ8(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $q8Groups = DB::table('q8_groups')
        ->where('condition','=', '1')
        ->select('id', 'nameQ8', 'period')
        ->orderBy('nameQ8', 'asc')
        ->get();

        return ['q8Groups' => $q8Groups];
    }

    public function toggle(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $q8 = Q8::findOrFail($request->id);
        $q8->condition = !$q8->condition;      
        $q8->save();
        
    }

    // function get_students_data($id)
    // {
    // $students = Q8::findOrFail($id)
    // ->join('students', 'students.id_q8', '=', 'q8_groups.id')
    // ->join('groups', 'students.id_group', '=', 'groups.id')
    // ->select('students.name as student_name', 'students.paternal_surname as lastName1', 'students.maternal_surname as lastname2', 
    // 'students.grade', 'groups.name as group_name')
    // ->where('students.condition', '!=', '0')
    // ->get();
    //  return $students;
    // }

    // function pdf (Request $request)
    // {
    //     $pdf = \App::make('dompdf.wrapper');
    //     $pdf->loadHTML($this->convert_to_html($request->id, $request->name));
    //     $pdf->stream();
    //     return $pdf->download('Reporte.pdf');

    //     // $students = $this->convert_to_html($request->id, $request->name);
    //     // $pdf2 = PDF::loadView('pdf', ['students' => $students]);
    //     // return $pdf2->download('Reporte.pdf');


    // }

    function convert_to_html($id, $name)
    {
        $students = $this->get_students_data($id);
        $output = '
        <h3 align="center">Alumnos inscritos en el grupo '.$name.' en el semestre '.date('Y').(date('n') <= 6 ? '1':'2').'</h3>
        <table width="100%" style="border-collapse: collapse; border: 0px;">
        <tr>
        <th style="border: 1px solid; padding:12px;" width="20%">Nombre</th>
        <th style="border: 1px solid; padding:12px;" width="30%">Grupo Extraescolar</th>
        <th style="border: 1px solid; padding:12px;" width="15%">Calificacion</th>
    </tr>
        ';  
        foreach($students as $student)
        {
            $fullName = $student->student_name. ' '. $student->lastName1. ' '.$student->lastName2;
            $output .= '
            <tr>
            <td style="border: 1px solid; padding:12px;">'.$fullName.'</td>
            <td style="border: 1px solid; padding:12px;">'.$student->group_name.'</td>
            <td style="border: 1px solid; padding:12px;">'.$student->grade.'</td>
            </tr>';
        }
        $output .= '</table>';
        return $students;
    }
}
