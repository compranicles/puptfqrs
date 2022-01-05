<?php

namespace App\Http\Controllers\Reports;

use App\Models\Dean;
use App\Models\Report;
use App\Models\DenyReason;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Authentication\UserRole;
use App\Models\Maintenance\Department;

class DeanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collegeHeadOf = Dean::select('deans.*', 'colleges.name as college_name')->
            join('colleges', 'deans.college_id', 'colleges.id')->where('user_id', auth()->id())->first();
        
        $reportsToReview = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
            ->join('departments', 'reports.department_id', 'departments.id')
            ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
            ->join('users', 'reports.user_id', 'users.id')
            ->where('reports.college_id', $collegeHeadOf->college_id)->where('chairperson_approval', 1)->where('dean_approval', null)->get();

        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $department_id = '';
        $college_id = '';
        if(in_array(5, $roles)){
            $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();
        }
        // dd($department_id);
        if(in_array(6, $roles)){
            $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
        }

        $employees = Report::join('users', 'reports.user_id', 'users.id')
                            ->where('reports.college_id', $collegeHeadOf->college_id)
                            ->select('users.last_name', 'users.first_name', 'users.suffix', 'users.middle_name')
                            ->where('reports.dean_approval', null)
                            ->distinct()
                            ->orderBy('users.last_name')
                            ->get();
        
        $departments = Department::
                            where('college_id', $collegeHeadOf->college_id)
                            ->orderBy('departments.name')
                            ->select('departments.id', 'departments.name')
                            ->get();

        return view('reports.deans.index', compact('collegeHeadOf', 'reportsToReview', 'roles', 'department_id', 'college_id', 'employees', 'departments'));
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
        //
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
    public function update(Request $request, $id)
    {
        //
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

    public function accept($report_id){
        Report::where('id', $report_id)->update(['dean_approval' => 1]);

        return redirect()->route('dean.index')->with('success', 'Report Accepted');
    }

    public function rejectCreate($report_id){
        return view('reports.deans.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        DenyReason::create([
            'report_id' => $report_id,
            'user_id' => auth()->id(),
            'position_name' => 'dean',
            'reason' => $request->input('reason'),
        ]);

        Report::where('id', $report_id)->update([
            'dean_approval' => 0
        ]);
        return redirect()->route('dean.index')->with('success', 'Report Denied');
    }

    public function relay($report_id){
        Report::where('id', $report_id)->update(['dean_approval' => 0]);
        return redirect()->route('submissions.denied.index')->with('deny-success', 'Report Denial successfully sent');
    }

    public function undo($report_id){
        Report::where('id', $report_id)->update(['dean_approval' => null]);
        return redirect()->route('submissions.denied.index')->with('deny-success', 'Success');
    }

    public function acceptSelected(Request $request){
        $reportIds = $request->input('report_id');

        foreach($reportIds as $id){
            Report::where('id', $id)->update(['dean_approval' => 1]);
        }
        return redirect()->route('dean.index')->with('success', 'Report/s Approved Successfully');
    }

    public function denySelected(Request $request){
        $reportIds = $request->input('report_id');
        return view('reports.deans.reject-select', compact('reportIds'));
    }

    public function rejectSelected(Request $request){
        $reportIds = $request->input('report_id');
        foreach($reportIds as $id){
            if($request->input('reason_'.$id) == null)
                continue;
            Report::where('id', $id)->update(['dean_approval' => 0]);
            DenyReason::create([
                'report_id' => $id,
                'user_id' => auth()->id(),
                'position_name' => 'dean',
                'reason' => $request->input('reason_'.$id),
            ]);
        }
        return redirect()->route('dean.index')->with('success', 'Report/s Denied Successfully');

    }
}
