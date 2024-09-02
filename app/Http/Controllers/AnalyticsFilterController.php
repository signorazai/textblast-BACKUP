<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\College;
use App\Models\Program;
use App\Models\Year;

class AnalyticsFilterController extends Controller
{
    public function getCollegesByCampus(Request $request)
    {
        $campusId = $request->input('campus_id');
        $colleges = College::where('campus_id', $campusId)->get();

        return response()->json($colleges);
    }

    public function getProgramsByCollege(Request $request)
    {
        $collegeId = $request->input('college_id');
        $programs = Program::where('college_id', $collegeId)->get();

        return response()->json($programs);
    }

    public function getAllYears()
    {
        $years = Year::all();

        return response()->json($years);
    }
}
