<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Answer;

class ExportController extends Controller
{
    public function export()
    {
        $answers = Answer::all();
        return Excel::download($answers, 'my-data.xlsx');
    }
}
