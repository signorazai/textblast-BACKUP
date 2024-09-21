<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Program;
use App\Models\Status;
use App\Models\Student;
use App\Models\Type;
use App\Models\Year;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /**
     * Retrieve filters based on the type (students/employees) and campus ID.
     *
     * @param string $type The type of data to fetch ('students', 'employees', 'all').
     * @param string $campusId The ID of the campus or 'all' for all campuses.
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Http\JsonResponse JSON response containing filter data.
     */
    public function getFilters($type, $campusId, Request $request)
    {
        // Get the recipient type from the query parameters, defaulting to 'both'
        $recipientType = $request->query('recipient_type', 'both');

        // Initialize an empty response array
        $response = [];

        if ($campusId === 'all') {
            // Fetch data for all campuses
            if ($type === 'students' || ($type === 'all' && $recipientType === 'students') || $recipientType === 'both') {
                // Fetch all colleges and years
                $response['colleges'] = College::all(['college_id as id', 'college_name as name']);
                $response['years'] = Year::all(['year_id as id', 'year_name as name']);
            }

            if ($type === 'employees' || ($type === 'all' && $recipientType === 'employees') || $recipientType === 'both') {
                // Fetch all offices and statuses
                $response['offices'] = Office::all(['office_id as id', 'office_name as name']);
                $response['statuses'] = Status::all(['status_id as id', 'status_name as name']);
            }
        } else {
            // Fetch data for a specific campus
            if ($type === 'students' || ($type === 'all' && $recipientType === 'students') || $recipientType === 'both') {
                // Fetch colleges associated with the specified campus and all years
                $response['colleges'] = College::where('campus_id', $campusId)->get(['college_id as id', 'college_name as name']);
                $response['years'] = Year::all(['year_id as id', 'year_name as name']);
            }

            if ($type === 'employees' || ($type === 'all' && $recipientType === 'employees') || $recipientType === 'both') {
                // Fetch offices associated with the specified campus and all statuses
                $response['offices'] = Office::where('campus_id', $campusId)->get(['office_id as id', 'office_name as name']);
                $response['statuses'] = Status::all(['status_id as id', 'status_name as name']);
            }
        }

        // Return the collected data as a JSON response
        return response()->json($response);
    }

    /**
     * Retrieve programs by a given college ID.
     *
     * @param string $collegeId The ID of the college or 'all' for all colleges.
     * @return \Illuminate\Http\JsonResponse JSON response containing programs.
     */
    public function getProgramsByCollege($collegeId)
    {
        // Fetch programs based on the college ID or all programs if 'all' is specified
        $programs = ($collegeId === 'all') 
            ? Program::all(['program_id as id', 'program_name as name'])
            : Program::where('college_id', $collegeId)->get(['program_id as id', 'program_name as name']);

        // Return the programs as a JSON response
        return response()->json(['programs' => $programs]);
    }

    /**
     * Retrieve types based on campus ID, office ID, and optional status ID.
     *
     * @param string $campusId The ID of the campus or 'all' for all campuses.
     * @param string $officeId The ID of the office or 'all' for all offices.
     * @param string|null $statusId Optional status ID or 'all' for all statuses.
     * @return \Illuminate\Http\JsonResponse JSON response containing types.
     */
    public function getTypesByOffice($campusId, $officeId, $statusId = null)
    {
        // Initialize the query for retrieving types
        $query = Type::query();

        // Apply filters based on campus ID, office ID, and status ID if not 'all'
        if ($campusId !== 'all') {
            $query->where('campus_id', $campusId);
        }

        if ($officeId !== 'all') {
            $query->where('office_id', $officeId);
        }

        if ($statusId && $statusId !== 'all') {
            $query->where('status_id', $statusId);
        }

        // Fetch the filtered types
        $types = $query->get(['type_id as id', 'type_name as name']);

        // Return the types as a JSON response
        return response()->json(['types' => $types]);
    }

    /**
     * Retrieve a list of contacts based on filters (students/employees) and campus ID.
     *
     * @param Request $request The HTTP request containing query parameters.
     * @return \Illuminate\Http\JsonResponse JSON response containing contacts.
     */
    public function getContacts(Request $request)
    {
        // Get the campus ID and filter from the request query parameters
        $campusId = $request->query('campus');
        $filter = $request->query('filter');

        // Initialize queries for students and employees
        $studentsQuery = Student::query();
        $employeesQuery = Employee::query();

        // Apply campus filter if specified and not 'all'
        if ($campusId && $campusId !== 'all') {
            $studentsQuery->where('campus_id', $campusId);
            $employeesQuery->where('campus_id', $campusId);
        }

        // Fetch data based on the filter type (students, employees, or both)
        if ($filter === 'students') {
            $results = $studentsQuery->get();
        } elseif ($filter === 'employees') {
            $results = $employeesQuery->get();
        } else {
            // Combine results for both students and employees
            $results = $studentsQuery->get()->concat($employeesQuery->get());
        }

        // Format the results for consistency between student and employee data
        $formattedResults = $results->map(function ($item) {
            return [
                'stud_fname' => $item->stud_fname ?? $item->emp_fname,
                'stud_lname' => $item->stud_lname ?? $item->emp_lname,
                'stud_mname' => $item->stud_mname ?? $item->emp_mname,
                'stud_contact' => $item->stud_contact ?? $item->emp_contact,
                'stud_email' => $item->stud_email ?? $item->emp_email,
            ];
        });

        // Return the formatted contact information as a JSON response
        return response()->json($formattedResults);
    }
}
