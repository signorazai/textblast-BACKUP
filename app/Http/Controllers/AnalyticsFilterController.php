<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\College;
use App\Models\Program;
use App\Models\Year;

class AnalyticsFilterController extends Controller
{
    /**
     * Get the list of colleges based on a specific campus ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollegesByCampus(Request $request)
    {
        // Retrieve the campus ID from the request
        $campusId = $request->input('campus_id');
        
        // Fetch all colleges that belong to the specified campus
        $colleges = College::where('campus_id', $campusId)->get();

        // Return the colleges as a JSON response
        return response()->json($colleges);
    }

    /**
     * Get the list of programs based on a specific college ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgramsByCollege(Request $request)
    {
        // Retrieve the college ID from the request
        $collegeId = $request->input('college_id');
        
        // Fetch all programs that belong to the specified college
        $programs = Program::where('college_id', $collegeId)->get();

        // Return the programs as a JSON response
        return response()->json($programs);
    }

    /**
     * Retrieve all available years from the database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllYears()
    {
        // Fetch all years from the 'years' table
        $years = Year::all();

        // Return the list of years as a JSON response
        return response()->json($years);
    }
}
