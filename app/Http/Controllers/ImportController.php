<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\College;
use App\Models\Program;
use App\Models\Major;
use App\Models\Year;

class ImportController extends Controller
{
    public function importCollege(Request $request)
    {
        try {
            $campusId = $request->campus_id;
            $remoteColleges = DB::connection('sqlsrv1')->table('dbo.vw_college_TB')->select('CollegeID', 'CollegeName')->get();
            $remoteCollegeIds = $remoteColleges->pluck('CollegeID')->toArray();

            // Delete local records that no longer exist in the remote data
            College::whereNotIn('college_id', $remoteCollegeIds)->delete();

            foreach ($remoteColleges as $remoteCollege) {
                // Use the remote CollegeID as the local college_id
                $localCollege = College::where('college_id', $remoteCollege->CollegeID)->first();

                if ($localCollege) {
                    // Update if there are changes
                    if ($localCollege->college_name !== $remoteCollege->CollegeName || $localCollege->campus_id !== $campusId) {
                        $localCollege->update([
                            'college_name' => $remoteCollege->CollegeName,
                            'campus_id' => $campusId,
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // Insert new record using remote CollegeID as the local college_id
                    College::create([
                        'college_id' => $remoteCollege->CollegeID,
                        'college_name' => $remoteCollege->CollegeName,
                        'campus_id' => $campusId,
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json(['success' => 'Colleges imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function importProgram(Request $request)
    {
        try {
            $campusId = $request->campus_id;
            $remotePrograms = DB::connection('sqlsrv1')->table('dbo.vw_es_programs_TB')->select('ProgID', 'ProgName', 'CollegeID')->get();
            $remoteProgramIds = $remotePrograms->pluck('ProgID')->toArray();

            // Delete local records that no longer exist in the remote data
            Program::whereNotIn('program_id', $remoteProgramIds)->delete();

            foreach ($remotePrograms as $remoteProgram) {
                // Ensure consistency of CollegeID from the remote database
                $college = College::where('college_id', $remoteProgram->CollegeID)->first();

                if ($college) {
                    $localProgram = Program::where('program_id', $remoteProgram->ProgID)->first();

                    if ($localProgram) {
                        // Update if there are changes
                        if ($localProgram->program_name !== $remoteProgram->ProgName || $localProgram->campus_id !== $campusId) {
                            $localProgram->update([
                                'program_name' => $remoteProgram->ProgName,
                                'college_id' => $remoteProgram->CollegeID,
                                'campus_id' => $campusId,
                                'updated_at' => now(),
                            ]);
                        }
                    } else {
                        // Insert new record using remote ProgramID as the local program_id
                        Program::create([
                            'program_id' => $remoteProgram->ProgID,
                            'program_name' => $remoteProgram->ProgName,
                            'college_id' => $remoteProgram->CollegeID,
                            'campus_id' => $campusId,
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    Log::warning("College ID {$remoteProgram->CollegeID} not found. Program ID {$remoteProgram->ProgID} skipped.");
                }
            }

            return response()->json(['success' => 'Programs imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function importMajor(Request $request)
    {
        try {
            $campusId = $request->campus_id;
            $remoteMajors = DB::connection('sqlsrv1')->table('dbo.vw_ProgramMajors_TB')->select('ProgID', 'MajorDiscID', 'CollegeID', 'Major')->get();
            $remoteMajorIds = $remoteMajors->pluck('MajorDiscID')->toArray();

            // Delete local records that no longer exist in the remote data
            Major::whereNotIn('major_id', $remoteMajorIds)->delete();

            foreach ($remoteMajors as $remoteMajor) {
                $program = Program::where('program_id', $remoteMajor->ProgID)->first();

                if ($program) {
                    $localMajor = Major::where('major_id', $remoteMajor->MajorDiscID)->first();

                    if ($localMajor) {
                        // Update if there are changes
                        if ($localMajor->major_name !== $remoteMajor->Major || $localMajor->campus_id !== $campusId) {
                            $localMajor->update([
                                'major_name' => $remoteMajor->Major,
                                'college_id' => $remoteMajor->CollegeID,
                                'program_id' => $remoteMajor->ProgID,
                                'campus_id' => $campusId,
                                'updated_at' => now(),
                            ]);
                        }
                    } else {
                        // Insert new record using remote MajorDiscID as the local major_id
                        Major::create([
                            'major_id' => $remoteMajor->MajorDiscID,
                            'major_name' => $remoteMajor->Major,
                            'college_id' => $remoteMajor->CollegeID,
                            'program_id' => $remoteMajor->ProgID,
                            'campus_id' => $campusId,
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    Log::warning("Program ID {$remoteMajor->ProgID} not found. Major ID {$remoteMajor->MajorDiscID} skipped.");
                }
            }

            return response()->json(['success' => 'Majors imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function importYear(Request $request)
    {
        try {
            $remoteYears = DB::connection('sqlsrv1')->table('dbo.vw_YearLevel_TB')->select('Yearlevelid', 'Yearlevel')->get();
            $remoteYearIds = $remoteYears->pluck('Yearlevelid')->toArray();

            // Delete local records that no longer exist in the remote data
            Year::whereNotIn('year_id', $remoteYearIds)->delete();

            foreach ($remoteYears as $remoteYear) {
                $localYear = Year::where('year_id', $remoteYear->Yearlevelid)->first();

                if ($localYear) {
                    // Update if there are changes
                    if ($localYear->year_name !== $remoteYear->Yearlevel) {
                        $localYear->update([
                            'year_name' => $remoteYear->Yearlevel,
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // Insert new record using remote Yearlevelid as the local year_id
                    Year::create([
                        'year_id' => $remoteYear->Yearlevelid,
                        'year_name' => $remoteYear->Yearlevel,
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json(['success' => 'Years imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
