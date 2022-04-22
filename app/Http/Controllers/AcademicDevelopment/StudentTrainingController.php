<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    StudentTraining,
    StudentTrainingDocument,
    TemporaryFile,
    FormBuilder\AcademicDevelopmentForm,
    Maintenance\Quarter,
};

class StudentTrainingController extends Controller
{
    protected $storageFileController;

    public function __construct(StorageFileController $storageFileController){
        $this->storageFileController = $storageFileController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', StudentTraining::class);
        $currentQuarterYear = Quarter::find(1);

        $student_trainings = StudentTraining::where('user_id', auth()->id())
                        ->orderBy('student_trainings.updated_at', 'desc')->get();

        return view('academic-development.student-training.index', compact('student_trainings', 'currentQuarterYear'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(4)");

        return view('academic-development.student-training.create', compact('studentFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', StudentTraining::class);

        $value = $request->input('budget');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $start_date = date("Y-m-d", strtotime($request->input('start_date')));
        $end_date = date("Y-m-d", strtotime($request->input('end_date')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'budget' => $value,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $request->validate([
            // 'budget' => 'numeric',
            'end_date' => 'after_or_equal:start_date',
            'total_hours' => 'numeric',
        ]);
        
        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);
        // dd($input);

        $student_training = StudentTraining::create($input);
        $student_training->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ST-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    StudentTrainingDocument::create([
                        'student_training_id' => $student_training->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Student attended seminar and training "'.$request->input('title').'" was added.');

        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StudentTraining $student_training)
    {
        $this->authorize('view', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(4)");

        $documents = StudentTrainingDocument::where('student_training_id', $student_training->id)->get()->toArray();

        $values = $student_training->toArray();

        return view('academic-development.student-training.show', compact('studentFields', 'student_training', 'documents', 'values'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentTraining $student_training)
    {
        $this->authorize('update', StudentTraining::class);

        if(LockController::isLocked($student_training->id, 19)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(4)");

        $documents = StudentTrainingDocument::where('student_training_id', $student_training->id)->get()->toArray();

        $values = $student_training->toArray();

        return view('academic-development.student-training.edit', compact('studentFields', 'student_training', 'documents', 'values'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentTraining $student_training)
    {
        $this->authorize('update', StudentTraining::class);

        $value = $request->input('budget');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $start_date = date("Y-m-d", strtotime($request->input('start_date')));
        $end_date = date("Y-m-d", strtotime($request->input('end_date')));

        $request->merge([
            'budget' => $value,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
        
        $request->validate([
            // 'budget' => 'numeric',
            'end_date' => 'after_or_equal:start_date',
            'total_hours' => 'numeric',
        ]);
        
        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);


        $student_training->update(['description' => '-clear']);

        $student_training->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ST-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    StudentTrainingDocument::create([
                        'student_training_id' => $student_training->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Student attended seminar and training "'.$student_training->title.'" was updated.');

        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentTraining $student_training)
    {
        $this->authorize('delete', StudentTraining::class);

        if(LockController::isLocked($student_training->id, 19)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        StudentTrainingDocument::where('student_training_id', $student_training->id)->delete();
        $student_training->delete();

        \LogActivity::addToLog('Student attended seminar and training "'.$student_training->title.'" was deleted.');

        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        StudentTrainingDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Student attended seminar and training document was deleted.');

        return true;
    }
}
