<x-app-layout>
    <x-slot name="header">
        @include('reports.navigation', compact('roles', 'departments', 'colleges', 'sectors'))
    </x-slot>

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">My Accomplishments</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="yearFilter" class="mr-2">Year Reported: </label>
                                    <select id="yearFilter" class="custom-select">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="quarterFilter" class="mr-2">Quarter Period: </label>
                                    <div class="d-flex">
                                        <select id="quarterFilter" class="custom-select" name="quarter">
                                            <option value="1" {{ $quarter == 1 ? 'selected' : ''  }} class="quarter">1</option>
                                            <option value="2" {{ $quarter == 2 ? 'selected' : ''  }} class="quarter">2</option>
                                            <option value="3" {{ $quarter == 3 ? 'selected' : ''  }} class="quarter">3</option>
                                            <option value="4" {{ $quarter == 4 ? 'selected' : ''  }} class="quarter">4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8" style="padding-top: 25px;">
                                <div class="form-group">
                                    <button id="filter" class="btn btn-primary mr-2"><i class="bi bi-list-ol"></i> Generate Table</button>
                                    <button id="export" type="button" class="btn btn-warning" data-target="#GenerateReport" data-toggle="modal"><i class="bi bi-filetype-xlsx"></i> Export QAR File</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" style="overflow-x: auto; padding-bottom: 8px;">
                                    <table class="table table-hover table-sm table-bordered" id="my_accomplishments_table">
                                        <thead>
                                            <tr>
                                                <th class="text-center" rowspan="2"></th>
                                                <th rowspan="2">Accomplishment Report</th>
                                                <th rowspan="2">Title</th>
                                                <th rowspan="2">Department/Section</th>
                                                <th class="text-center" colspan="6">Status</th>
                                                <th rowspan="2"></th>
    
                                            </tr>
                                            <tr class="text-center">
                                                <th>Researcher</th>
                                                <th>Extensionist</th>
                                                <th>Chair/Chief</th>
                                                <th>Dean/<br>Director</th>
                                                <th>Sector Head</th>
                                                <th>IPO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($my_accomplishments as $row)
                                            <tr role="button" class="button-view" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">
                                                <td class="report-view text-center">{{ $loop->iteration }}</td>
                                                <td class="report-view">{{ $row->report_category }}</td>
                                                <td class="report-view">
                                                    @if (isset($row->report_details->title))
                                                        {{ $row->report_details->title }}
                                                    @elseif (isset($row->report_details->publication_or_audio_visual))
                                                        {{ $row->report_details->publication_or_audio_visual }}
                                                    @elseif (isset($row->report_details->title_of_extension_program))
                                                        {{ $row->report_details->title_of_extension_program }}
                                                    @elseif (isset($row->report_details->title_of_extension_project))
                                                        {{ $row->report_details->title_of_extension_project }}
                                                    @elseif (isset($row->report_details->title_of_extension_activity))
                                                        {{ $row->report_details->title_of_extension_activity }}
                                                    @elseif (isset($row->report_details->title_of_partnership))
                                                        {{ $row->report_details->title_of_partnership }}
                                                    @elseif (isset($row->report_details->mobility_description))
                                                        {{ $row->report_details->mobility_description }}
                                                    @elseif (isset($row->report_details->course_title))
                                                        {{ $row->report_details->course_title }}
                                                    @elseif (isset($row->report_details->description_of_request))
                                                        {{ $row->report_details->description_of_request }}
                                                    @elseif (isset($row->report_details->name_of_award))
                                                        {{ $row->report_details->name_of_award }}
                                                    @elseif (isset($row->report_details->name))
                                                        {{ $row->report_details->name }}
                                                    @elseif (isset($row->report_details->title_of_the_program))
                                                        {{ $row->report_details->title_of_the_program }}
                                                    @elseif (isset($row->report_details->output))
                                                        {{ $row->report_details->output }}
                                                    @elseif (isset($row->report_details->final_output))
                                                        {{ $row->report_details->final_output }}
                                                    @elseif (isset($row->report_details->activity_description))
                                                        {{ $row->report_details->activity_description }}
                                                    @elseif (isset($row->report_details->active_linkages))
                                                        {{ $row->report_details->active_linkages }}
                                                    @elseif (isset($row->report_details->program_title))
                                                        {{ $row->report_details->program_title }}
                                                    @elseif (isset($row->report_details->project_title))
                                                            {{ $row->report_details->project_title }}
                                                    @elseif (isset($row->report_details->activity_title))
                                                        {{ $row->report_details->activity_title }}
                                                    @elseif (isset($row->report_details->accomplishment_description))
                                                        {{ $row->report_details->accomplishment_description }}
                                                    @elseif (isset($row->report_details->award))
                                                        {{ $row->report_details->award }}
                                                    @elseif (isset($row->report_details->organization))
                                                        {{ $row->report_details->organization }}
                                                    @elseif (isset($row->report_details->degree))
                                                        {{ $row->report_details->degree }}
                                                    @endif
                                                </td>
                                                {{-- <td class="report-view">{{ $college_names[$row->id] ?? '-' }}</td> --}}
                                                <td class="report-view">{{ $department_names[$row->id] ?? '-' }}</td>
                                                <td class="report-view text-center">
                                                    @if ($row->report_category_id >= 1 && $row->report_category_id <= 8)
                                                        @if ($row->researcher_approval === null)
                                                            @if ($row->format == 'f')
                                                                Receiving...
                                                            @else
                                                                N/A
                                                            @endif
                                                        @elseif ($row->researcher_approval == 0)
                                                            <span class="text-danger font-weight-bold">Returned</span>
                                                        @elseif ($row->researcher_approval == 1)
                                                            <span class="text-success font-weight-bold">Reviewed</span>
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="report-view text-center">
                                                    @if (($row->report_category_id >= 12 && $row->report_category_id <= 14) || ($row->report_category_id >= 34 && $row->report_category_id <= 37) || $row->report_category_id == 22 || $row->report_category_id == 23)
                                                        @if ($row->extensionist_approval === null)
                                                            @if ($row->format == 'f')
                                                                Receiving...
                                                            @else
                                                                N/A
                                                            @endif
                                                        @elseif ($row->extensionist_approval == 0)
                                                            <span class="text-danger font-weight-bold">Returned</span>
                                                        @elseif ($row->extensionist_approval == 1)
                                                            <span class="text-success font-weight-bold">Reviewed</span>
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="report-view text-center">
                                                    @if ($row->format == 'a')
                                                        @if ($row->report_category_id >= 1 && $row->report_category_id <= 8)
                                                            @if ($row->chairperson_approval === null)
                                                                @if ($row->department_id == $row->college_id)
                                                                    N/A
                                                                @else
                                                                    Receiving...
                                                                @endif
                                                            @elseif ($row->chairperson_approval === 0)
                                                                <span class="text-danger font-weight-bold">Returned</span>
                                                            @else
                                                                @if ($row->department_id == $row->college_id)
                                                                    <span class="text-success font-weight-bold">Viewed</span>
                                                                @else
                                                                    <span class="text-success font-weight-bold">Reviewed</span>
                                                                @endif
                                                            @endif
                                                        @elseif (($row->report_category_id >= 12 && $row->report_category_id <= 14) || ($row->report_category_id >= 34 && $row->report_category_id <= 37) || $row->report_category_id == 22 || $row->report_category_id == 23)
                                                            @if ($row->chairperson_approval === null)
                                                                @if ($row->department_id == $row->college_id)
                                                                    N/A
                                                                @else
                                                                    Receiving...
                                                                @endif
                                                            @elseif ($row->chairperson_approval === 0)
                                                                <span class="text-danger font-weight-bold">Returned</span>
                                                            @else
                                                                @if ($row->department_id == $row->college_id)
                                                                    <span class="text-success font-weight-bold">Viewed</span>
                                                                @else
                                                                    <span class="text-success font-weight-bold">Reviewed</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if ($row->chairperson_approval === null)
                                                                @if ($row->department_id == $row->college_id)
                                                                    N/A
                                                                @else
                                                                    Receiving...
                                                                @endif
                                                            @elseif ($row->chairperson_approval === 0)
                                                                <span class="text-danger font-weight-bold">Returned</span>
                                                            @else
                                                                @if ($row->department_id == $row->college_id)
                                                                    <span class="text-success font-weight-bold">Viewed</span>
                                                                @else
                                                                    <span class="text-success font-weight-bold">Reviewed</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @elseif ($row->format == 'f')
                                                        @if ($row->report_category_id >= 1 && $row->report_category_id <= 8)
                                                            @if ($row->chairperson_approval === null)
                                                                N/A
                                                            @elseif ($row->chairperson_approval === 0)
                                                                <span class="text-danger font-weight-bold">Returned</span>
                                                            @else
                                                                <span class="text-success font-weight-bold">Viewed</span>
                                                            @endif
                                                        @elseif (($row->report_category_id >= 12 && $row->report_category_id <= 14) || ($row->report_category_id >= 34 && $row->report_category_id <= 37) || $row->report_category_id == 22 || $row->report_category_id == 23)
                                                            @if ($row->chairperson_approval === null)
                                                                N/A
                                                            @elseif ($row->chairperson_approval === 0)
                                                                <span class="text-danger font-weight-bold">Returned</span>
                                                            @else
                                                                <span class="text-success font-weight-bold">Viewed</span>
                                                            @endif
                                                        @else
                                                            @if ($row->chairperson_approval === null)
                                                                Receiving...
                                                            @elseif ($row->chairperson_approval === 0)
                                                                <span class="text-danger font-weight-bold">Returned</span>
                                                            @else
                                                                @if ($row->department_id == $row->college_id)
                                                                    @if ($row->department_id >= 227 && $row->department_id <= 248)
                                                                        <span class="text-success font-weight-bold">Reviewed</span>
                                                                    @else
                                                                        <span class="text-success font-weight-bold">Viewed</span>
                                                                    @endif
                                                                @else
                                                                    <span class="text-success font-weight-bold">Reviewed</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="report-view text-center">
                                                    @if ($row->chairperson_approval === 0)
                                                        -
                                                    @elseif ($row->chairperson_approval === null)
                                                        -
                                                    @else
                                                        @if ($row->dean_approval === null)
                                                            Receiving...
                                                        @elseif ($row->dean_approval === 0)
                                                            <span class="text-danger font-weight-bold">Returned</span>
                                                        @elseif ($row->dean_approval === 1)
                                                            <span class="text-success font-weight-bold">Reviewed</span>
                                                        @elseif ($row->dean_approval === 2)
                                                            <span class="text-success font-weight-bold">Reviewed By Associate/Assistant</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="report-view text-center">
                                                    @if ($row->dean_approval === 0)
                                                        -
                                                    @elseif ($row->dean_approval === null)
                                                        -
                                                    @else
                                                        @if ($row->sector_approval === null)
                                                            Receiving...
                                                        @elseif ($row->sector_approval === 0)
                                                            <span class="text-danger font-weight-bold">Returned</span>
                                                        @elseif ($row->sector_approval === 1)
                                                            <span class="text-success font-weight-bold">Reviewed</span>
                                                        @elseif ($row->sector_approval === 2)
                                                            <span class="text-success font-weight-bold">Reviewed by Assistant to VP</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="report-view text-center">
                                                    @if ($row->sector_approval === 0)
                                                        -
                                                    @elseif ($row->sector_approval === null)
                                                        -
                                                    @else
                                                        @if ($row->ipqmso_approval === null)
                                                            Receiving...
                                                        @elseif ($row->ipqmso_approval === 0)
                                                            <span class="text-danger font-weight-bold">Returned</span>
                                                        @elseif ($row->ipqmso_approval === 1)
                                                            <span class="text-success font-weight-bold">Received</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($row->report_category_id >= 1 && $row->report_category_id <= 8)
                                                        @if (
                                                            $row->researcher_approval === 0 ||
                                                            $row->chairperson_approval === 0 ||
                                                            $row->dean_approval === 0 ||
                                                            $row->sector_approval === 0 ||
                                                            $row->ipqmso_approval === 0
                                                        )
                                                            <button class="button-deny action-remarks" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}"><i class="bi bi-chat-square-text" style="font-size: 1.25em;"></i> Remarks</button>
                                                            <br>
                                                            <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" onclick="{{ session(['myaccomplishment_url' => url()->current()]) }}" class="action-edit" id="view_accomp_documents" data-id="{{ $row->id }}"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i> Edit</a>
                                                        @else
                                                            -
                                                        @endif
                                                    @elseif (($row->report_category_id >= 12 && $row->report_category_id <= 14) || ($row->report_category_id >= 34 && $row->report_category_id <= 37) || $row->report_category_id == 22 || $row->report_category_id == 23)
                                                        @if (
                                                            $row->extensionist_approval === 0 ||
                                                            $row->chairperson_approval === 0 ||
                                                            $row->dean_approval === 0 ||
                                                            $row->sector_approval === 0 ||
                                                            $row->ipqmso_approval === 0
                                                        )
                                                            <button class="button-deny action-remarks" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}"><i class="bi bi-chat-square-text" style="font-size: 1.25em;"></i> Remarks</button>
                                                            <br>
                                                            <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" onclick="{{ session(['myaccomplishment_url' => url()->current()]) }}" class="action-edit" id="view_accomp_documents" data-id="{{ $row->id }}"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i> Edit</a>
                                                        @else
                                                            -
                                                        @endif
                                                    @else
                                                        @if (
                                                            $row->chairperson_approval === 0 ||
                                                            $row->dean_approval === 0 ||
                                                            $row->sector_approval === 0 ||
                                                            $row->ipqmso_approval === 0
                                                        )
                                                            <button class="button-deny action-remarks" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}"><i class="bi bi-chat-square-text" style="font-size: 1.25em;"></i> Remarks</button>
                                                            <br>
                                                            <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" onclick="{{ session(['myaccomplishment_url' => url()->current()]) }}" class="action-edit" id="view_accomp_documents" data-id="{{ $row->id }}"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i> Edit</a>
                                                        @else
                                                            -
                                                        @endif
                                                    @endif
    
                                                </td>
    
                                            </tr>
                                            @empty
    
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('reports.generate.index', ['data' => $user, 'level' => 'individual', 'colleges' => $collegeList, 'special_type' => '', 'roles' => $roles])


    <div class="modal fade" id="viewReport" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="viewReportLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-100 text-center">
                <div class="row justify-content-center">
                    <div class="col-md-11 h5 font-weight-bold">Documents</div>
                    <div class="col-md-11" id="data_documents">
                    </div>
                </div>
                <hr>
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <table class="table table-sm table-borderless" id="columns_value_table">
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center" id="review_btn_undo">
                    </div>
                    <div class="col-md-12 text-center" id="review_btn_relay">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewDeny" tabindex="-1" aria-labelledby="viewDenyLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDenyLabel">Returned Accomplishment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <table class="table table-sm table-borderless" style="width: 100%" id="view_deny_details_table">
                            <tr id="deny-detail-1">
                                <td class="font-weight-bold">Returned By:</td>
                            </tr>
                            <tr id="deny-detail-2">
                                <td class="font-weight-bold">Date:</td>
                            </tr>
                            <tr id="deny-detail-3">
                                <td class="font-weight-bold">Reason:</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).on('click', '.button-view', function(event){
                event.stopPropagation();
                var catID = $(this).data('id');
                var link = $(this).data('url');

                var countColumns = 0;
                var url = "{{ url('/reports/data/:id/') }}";
				var newlink = url.replace(':id', catID);
				$.get(newlink, function (data){
                    Object.keys(data).forEach(function(k){
                        countColumns = countColumns + 1;
                        $('#columns_value_table').append('<tr id="row-'+countColumns+'" class="d-flex report-content"></tr>');
                        $('#row-'+countColumns).append('<td class="report-content font-weight-bold text-right" width="50%">'+k+':</td>');
                        $('#row-'+countColumns).append('<td class="report-content text-left">'+data[k]+'</td>');
                    });
                });
                var urldoc = "{{ url('/reports/docs/:id/') }}";
				var newlinkdoc = urldoc.replace(':id', catID);
				$.get(newlinkdoc, function (data) {
					data.forEach(function (item){
                        var newlink = link.replace(':filename', item);
                        $('#data_documents').append('<a href="'+newlink+'" target="_blank" class="report-content h5 m-1 btn btn-primary">'+item+'<a/>');
                    });
                });

                var viewReport = document.getElementById('viewReport');
                var reportCategory = $(this).data('report-category');
                var modalTitle = viewReport.querySelector('.modal-title');
                modalTitle.textContent = reportCategory;

            });

            $(document).on('click', '.button-deny', function () {
                var categoryID = $(this).data('id');

                var urldetails = "{{ url('/reports/reject-details/:id/') }}";
				var newlink2 = urldetails.replace(':id', categoryID);
				$.get(newlink2, function (data) {
                    var position = data.position_name;
                    var countColumns = 1;
                    var position_name = position.charAt(0).toUpperCase()+position.slice(1);
                    $('#deny-detail-'+countColumns).append('<td class="report-content text-left">'+position_name+'</td>');
                    countColumns = countColumns + 1;
                    $('#deny-detail-'+countColumns).append('<td class="report-content text-left">'+data.time+'</td>');
                    countColumns = countColumns + 1;
                    $('#deny-detail-'+countColumns).append('<td class="report-content text-left">'+data.reason+'</td>');
                });
            });

            $('#viewReport').on('hidden.bs.modal', function(event) {
                $('.report-content').remove();
            });

            $('#viewDeny').on('hidden.bs.modal', function(event) {
                $('#deny-details').remove();
                $('.report-content').remove();
            });
            $(function(){
                $('#my_accomplishments_table').DataTable();
            });

            // auto hide alert
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);
        </script>
        <script>
            //auto-iteration of years for filter
            var max = {!! json_encode($year) !!};
            var min = 0;
            var diff = max-2022;
            min = max-diff;
            select = document.getElementById('yearFilter');

            var year = {!! json_encode($year) !!};
            for (var i = max; i >= min; i--) {
                select.append(new Option(i, i));
                if (year == i) {
                    document.getElementById("yearFilter").value = i;
                }
            }
        </script>
        <script>
            $('#filter').on('click', function () {
                var year_reported = $('#yearFilter').val();
                var quarter = $('#quarterFilter').val();
                var link = "{{ url('/reports/consolidate/my-accomplishments/reportYearFilter/:year/:quarter/') }}";
                var newLink = link.replace(':year', year_reported).replace(':quarter', quarter);
                window.location.replace(newLink);
            });
        </script>
        <script>
            $('#export').on('click', function() {
                var selectedQuarter = $('#quarterFilter').val();
                var selectedYear = $('#yearFilter').val();
                $('#quarter_generate').val(selectedQuarter);
                $('#year_generate').val(selectedYear);
            })
        </script>
    @endpush
</x-app-layout>
