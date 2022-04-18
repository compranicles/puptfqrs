<?php

namespace App\Http\Controllers\Reports\Consolidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Dean,
    Report,
    User,
    Authentication\UserRole,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};
use App\Services\ManageConsolidatedReportAuthorizationService;

class CollegeConsolidatedController extends Controller
{
    public function index($id){
        $authorize = (new ManageConsolidatedReportAuthorizationService())->authorizeManageConsolidatedReportsByCollege();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $departments = [];
        $colleges = [];
        $sectors = [];
        $departmentsResearch = [];
        $departmentsExtension = [];

        $currentQuarterYear = Quarter::find(1);
        $quarter = $currentQuarterYear->current_quarter;
        $year = $currentQuarterYear->current_year;

        if(in_array(5, $roles)){
            $departments = Chairperson::where('chairpeople.user_id', auth()->id())->select('chairpeople.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'chairpeople.department_id')->get();
        }
        if(in_array(6, $roles)){
            $colleges = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.code')
                            ->join('colleges', 'colleges.id', 'deans.college_id')->get();
        }
        if(in_array(7, $roles)){
            $sectors = SectorHead::where('sector_heads.user_id', auth()->id())->select('sector_heads.sector_id', 'sectors.code')
                        ->join('sectors', 'sectors.id', 'sector_heads.sector_id')->get();
        }
        if(in_array(10, $roles)){
            $departmentsResearch = FacultyResearcher::where('faculty_researchers.user_id', auth()->id())
                                        ->select('faculty_researchers.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_researchers.department_id')->get();
        }
        if(in_array(11, $roles)){
            $departmentsExtension = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                        ->select('faculty_extensionists.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_extensionists.department_id')->get();
        }

        $college_accomps = 
            Report::select(
                            'reports.*', 
                            'report_categories.name as report_category', 
                            'users.last_name', 
                            'users.first_name',
                            'users.middle_name', 
                            'users.suffix'
                          )
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'users.id', 'reports.user_id')
                ->where('reports.report_year', $year)
                ->where('reports.report_quarter', $quarter)
                ->where('reports.college_id', $id)->get();

        //get_department_and_college_name
        $college_names = [];
        $department_names = [];
        foreach($college_accomps as $row){
            $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
            $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();


            if($temp_college_name == null)
                $college_names[$row->id] = '-';
            else
                $college_names[$row->id] = $temp_college_name;
            if($temp_department_name == null)
                $department_names[$row->id] = '-';
            else
                $department_names[$row->id] = $temp_department_name;
        }
        
        $user = User::find(auth()->id());
        //collegedetails
        $college = College::find($id);

        return view(
                    'reports.consolidate.college', 
                    compact('roles', 'departments', 'colleges', 'college_accomps', 'college' , 
                        'department_names', 'college_names', 'sectors', 'departmentsResearch',
                        'departmentsExtension', 'quarter', 'year', 'id', 'user')
                );
    }
    
    public function collegeReportYearFilter($college, $year, $quarter) {
        if ($year == "default") {
            return redirect()->route('reports.consolidate.college');
        }
        else{
            $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
            $departments = [];
            $colleges = [];
            $sectors = [];
            $departmentsResearch = [];
            $departmentsExtension = [];

            if(in_array(5, $roles)){
                $departments = Chairperson::where('chairpeople.user_id', auth()->id())->select('chairpeople.department_id', 'departments.code')
                                            ->join('departments', 'departments.id', 'chairpeople.department_id')->get();
            }
            if(in_array(6, $roles)){
                $colleges = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.code')
                                ->join('colleges', 'colleges.id', 'deans.college_id')->get();
            }
            if(in_array(7, $roles)){
                $sectors = SectorHead::where('sector_heads.user_id', auth()->id())->select('sector_heads.sector_id', 'sectors.code')
                            ->join('sectors', 'sectors.id', 'sector_heads.sector_id')->get();
            }
            if(in_array(10, $roles)){
                $departmentsResearch = FacultyResearcher::where('faculty_researchers.user_id', auth()->id())
                                            ->select('faculty_researchers.department_id', 'departments.code')
                                            ->join('departments', 'departments.id', 'faculty_researchers.department_id')->get();
            }
            if(in_array(11, $roles)){
                $departmentsExtension = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                            ->select('faculty_extensionists.department_id', 'departments.code')
                                            ->join('departments', 'departments.id', 'faculty_extensionists.department_id')->get();
            }

            $college_accomps = 
                Report::select(
                                'reports.*', 
                                'report_categories.name as report_category', 
                                'users.last_name', 
                                'users.first_name',
                                'users.middle_name', 
                                'users.suffix'
                            )
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                    ->join('users', 'users.id', 'reports.user_id')
                    ->where('reports.report_year', $year)
                    ->where('reports.report_quarter', $quarter)
                    ->where('reports.college_id', $college)->get();

            //get_department_and_college_name
            $college_names = [];
            $department_names = [];
            foreach($college_accomps as $row){
                $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
                $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();


                if($temp_college_name == null)
                    $college_names[$row->id] = '-';
                else
                    $college_names[$row->id] = $temp_college_name;
                if($temp_department_name == null)
                    $department_names[$row->id] = '-';
                else
                    $department_names[$row->id] = $temp_department_name;
            }
            
            $user = User::find(auth()->id());
            //collegedetails
            $college = College::find($college);

            return view(
                        'reports.consolidate.college', 
                        compact('roles', 'departments', 'colleges', 'college_accomps', 'college' , 'department_names',
                             'college_names', 'sectors', 'departmentsResearch','departmentsExtension', 'quarter', 'year',
                             'user')
                    );
        }
    }
}
