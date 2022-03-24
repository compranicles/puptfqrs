<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Maintenance\GenerateTable;
use App\Models\Maintenance\GenerateColumn;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Services\NameConcatenationService;

class CollegeConsolidatedAccomplishmentReportExport implements FromView, WithEvents
{
    function __construct($source_type, $reportFormat, $source_generate, $year_generate, $quarter_generate,
         $id, $cbco, $faculty_researchers, $faculty_extensionists, $chairpeople, $table_columns, 
         $table_contents, $table_format) {
        $this->source_type = $source_type;
        $this->report_format = $reportFormat;
        $this->source_generate = $source_generate;
        $this->year_generate = $year_generate;
        $this->quarter_generate = $quarter_generate;
        $this->id = $id;
        $this->cbco = $cbco;
        $this->faculty_researchers = $faculty_researchers;
        $this->faculty_extensionists = $faculty_extensionists;
        $this->chairpeople = $chairpeople;
        $this->table_columns = $table_columns;
        $this->table_contents = $table_contents;
        $this->table_format = $table_format;

        $user = User::where('id', auth()->id())->first();
        $this->arranged_name = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($user['id'], " ");
        $this->fr_name = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($faculty_researchers['user_id'], "Faculty Researcher");
        $this->fe_name = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($faculty_extensionists['user_id'], "Faculty Extensionist");
        $this->chairperson_name = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($chairpeople['user_id'], "Chairperson");
    }

    public function view(): View
    {
        $source_type = $this->source_type;
        $reportFormat = $this->report_format;
        $source_generate = $this->source_generate;
        $year_generate = $this->year_generate;
        $quarter_generate = $this->quarter_generate;
        $id = $this->id;
        $table_format;
        $table_columns;
        $table_contents;
        $data;
        $source_type;
        
        if($reportFormat == "academic"){
            if($source_generate == "college"){
                $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
                $table_format = GenerateTable::where('type_id', 2)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.college_id', $college_id)
                            ->where('reports.dean_approval', 1)
                            ->where('reports.report_year', $year_generate)
                            ->where('reports.report_quarter', $quarter_generate)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
        }
        elseif($reportFormat == "admin"){
            if($source_generate == "college"){
                $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
                $table_format = GenerateTable::where('type_id', 1)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.college_id', $college_id)
                            ->where('reports.dean_approval', 1)
                            ->where('reports.report_year', $year_generate)
                            ->where('reports.report_quarter', $quarter_generate)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            
        }

        $this->table_format = $table_format;
        $this->table_columns = $table_columns;
        $this->table_contents = $table_contents;
        return view('reports.generate.example', compact('table_format', 'table_columns', 'table_contents', 'source_type', 'data', 'reportFormat', 'source_generate', 'year_generate', 'quarter_generate', 'id'));
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(Aftersheet $event) {
                $event->sheet->getSheetView()->setZoomScale(70);
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Arial');
                // $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(12);
                $event->sheet->getDefaultColumnDimension()->setWidth(33);
                // $event->sheet->getStyle('A1:Z500')->getAlignment()->setWrapText(true);
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->freezePane('B1');
                if ($this->source_type == "individual")
                    if ($this->report_format == "academic")
                    {   
                        $event->sheet->setCellValue('A1', 'FACULTY INDIVIDUAL ACCOMPLISHMENT REPORT');
                        $event->sheet->getStyle('A1')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 20,
                            ]
                        ]);
                    }
                    else {
                        $event->sheet->setCellValue('A1', 'ADMIN INDIVIDUAL ACCOMPLISHMENT REPORT');
                        $event->sheet->getStyle('A1')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 20,
                            ]
                        ]);
                    }
                else {
                    $event->sheet->setCellValue('A1', 'CONSOLIDATED QUARTERLY ACCOMPLISHMENT REPORT');
                    $event->sheet->getStyle('A1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 20,
                        ]
                    ]);
                }

                $event->sheet->getStyle('A1:Z500')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                // $event->sheet->getRowDimension('1')->setRowHeight(26.25);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->mergeCells('B2:C2');
                $event->sheet->setCellValue('B2', 'COLLEGE/BRANCH/CAMPUS/OFFICE:');
                $event->sheet->getStyle('B2')->applyFromArray([
                    'font' => [
                        'size' => 16,
                        'bold' => true,
                    ]
                ]);
                $event->sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('D2:F2');
                $event->sheet->setCellValue('D2', $this->cbco);
                $event->sheet->getStyle('D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D2:F2')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('D2')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);

                // Name
                $event->sheet->setCellValue('C3', 'CHAIRPERSON:');
                $event->sheet->getStyle('C3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('D3:F3');
                $event->sheet->setCellValue('D3', $this->arranged_name);
                $event->sheet->getStyle('D3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D3:F3')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('D3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);

                $event->sheet->setCellValue('C4', 'QUARTER:');
                $event->sheet->getStyle('C4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('C4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('D4', $this->quarter_generate);
                $event->sheet->getStyle('D4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('D4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $event->sheet->setCellValue('E4', 'CALENDAR YEAR:');
                $event->sheet->getStyle('E4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('F4', $this->year_generate);
                $event->sheet->getStyle('F4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('F4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('F4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $count = 7;
                $table_format = $this->table_format;
                $table_columns = $this->table_columns;
                $table_contents = $this->table_contents;
                foreach($table_format as $format) {
                    if($format->is_table == '0'){
                        
                        //title
                        $event->sheet->mergeCells('A'.$count.':K'.$count);
                        $event->sheet->getStyle('A'.$count)->getAlignment()->setWrapText(true);
                        $event->sheet->getStyle('A'.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFC00000");
                        $event->sheet->getStyle('A'.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                        $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $event->sheet->getRowDimension($count)->setRowHeight(30);
                        $event->sheet->getStyle('A'.$count)->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                            ]
                        ]);
                        $count++;

                    }
                    elseif($format->is_table == '1') {
                        $length = count($table_columns[$format->id]);
                        if ($length == null){
                            $length = 2;
                        }
                        else{
                            $length = $length+4;
                        }
                        $letter = Coordinate::stringFromColumnIndex($length);

                        // title
                        $event->sheet->mergeCells('A'.$count.':'.$letter.$count);
                        $event->sheet->getStyle('A'.$count)->getAlignment()->setWrapText(true);
                        // $event->sheet->getStyle('A'.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FF800000");
                        if ($format->is_individual == '0') {
                            $event->sheet->getStyle('A'.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FF002060");
                            $event->sheet->getStyle('A'.$count)->getFont()->getColor()->setARGB('ffffffff');
                        }
                        else {
                            $event->sheet->getStyle('A'.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFFC000");
                            $event->sheet->getStyle('A'.$count)->getFont()->getColor()->setARGB('FFC00000');
                        }
                        
                        // $event->sheet->getStyle('A'.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

                        $event->sheet->getRowDimension($count)->setRowHeight(30);
                        $event->sheet->getStyle('A'.$count)->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                            ]
                        ]);
                        $count++;

                        //column
                        $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                        $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                                'bold' => true, 
                                'size' => 14
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF515256'],
                                ],
                            ],
                        ]);
                        $count++;

                        //contents
                        foreach($table_contents[$format->id] as $contents){
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ],
                            ]);
                            $count++;
                        }

                        if($table_contents[$format->id] == null){
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            // $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFD9E1F2");
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        // 'color' => ['argb' => 'FF515256'],
                                    ],
                                ],
                            ]);
                            $count++;
                        }

                        $footers = json_decode($format->footers);
                        if ($footers != null){
                            foreach ($footers as $footer){
                                $event->sheet->getStyle('A'.$count)->applyFromArray([
                                    'font' => [
                                        'name' => 'Arial',
                                    ]
                                ]);
                                $count++;
                            }
                        }
                        
                        $count += 2;
                    }
                }
                $count = $count + 2;
                $event->sheet->setCellValue('A'.$count, 'Supporting Evidence Verified By:');
                $event->sheet->setCellValue('G'.$count, 'Prepared By:');
                $event->sheet->getStyle('A'.$count.':G'.$count)->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'bold' => true, 
                        'size' => 14
                    ],
                ]);
                $count = $count + 5;
                $event->sheet->setCellValue('A'.$count, $this->fr_name);
                $event->sheet->setCellValue('C'.$count, $this->fe_name);
                $event->sheet->setCellValue('E'.$count, $this->chairperson_name);
                $event->sheet->setCellValue('G'.$count, $this->arranged_name);
                $event->sheet->getStyle('A'.$count.':G'.$count)->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'bold' => true, 
                        'size' => 14
                    ],
                ]);
                $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('E'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('G'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $count = $count + 1;
                $event->sheet->setCellValue('A'.$count, 'Faculty Researcher, '.$this->faculty_researchers->department_name);
                $event->sheet->setCellValue('C'.$count, 'Faculty Extensionist, '.$this->faculty_extensionists->department_name);
                $event->sheet->setCellValue('E'.$count, 'Chairperson, '.$this->chairpeople->department_name);
                $event->sheet->setCellValue('G'.$count, 'Director, '.$this->cbco);
                $event->sheet->getStyle('A'.$count.':G'.$count)->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'bold' => true, 
                        'size' => 14
                    ],
                ]);

                $event->sheet->getStyle('A'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('C'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('E'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('G'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('E'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('G'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

        ];
    }
}
