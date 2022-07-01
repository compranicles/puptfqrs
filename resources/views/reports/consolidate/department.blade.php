<x-app-layout>
    <x-slot name="header">
        @include('reports.navigation', compact('roles', 'departments', 'colleges', 'sectors', 'id'))
    </x-slot>
    <div class="row">
        <div class="col-md-12">
            <h2 class="font-weight-bold mb-2">Consolidated QAR - {{ $department->code }} Department</h2>
        </div>
    </div>
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
                <div class="col-md-8" style="padding-top: 30px;">
                    <div class="form-group">
                        <form action="{{ route('report.generate.index', $user->id)}}" method="POST" id="export_level_form">
                            @csrf
                            <input type="hidden" name="source_generate" value="my">
                            <input type="hidden" name="type_generate" value="department_level">
                            <input type="hidden" id="ex_quar" name="quarter_generate" value="">
                            <input type="hidden" id="ex_year" name="year_generate_level" value="">
                            <button id="filter" type="button" class="btn btn-primary">GENERATE</button>
                            <button id="export" type="button" class="btn btn-primary ml-2 mr-2" data-target="#GenerateReport" data-toggle="modal">EXPORT</button>
                            <button id="exportLevel" type="button" class="btn btn-primary">EXPORT (QAR FILLED IN BY CHAIRPERSON)</button>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" style="overflow-x: auto; padding-bottom: 8px;">
                        <table class="table table-hover table-sm table-bordered" id="department_accomplishments_table">
                            <thead>
                                <tr>
                                    <th rowspan="2"></th>
                                    <th rowspan="2">Accomplishment Report</th>
                                    <th rowspan="2">Title</th>
                                    <th rowspan="2">Employee</th>
                                    {{-- <th rowspan="2">College/Branch/Campus/Office</th> --}}
                                    <!-- <th rowspan="2">Department</th> -->
                                    <th class="text-center" colspan="6">Status</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr class="text-center">
                                    <th>Researcher</th>
                                    <th>Extensionist</th>
                                    <th>Chairperson</th>
                                    <th>Dean/<br>Director</th>
                                    <th>Sector Head</th>
                                    <th>IPO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($department_accomps as $row)
                                <tr role="button">
                                    <td class="report-view button-view text-center" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">{{ $loop->iteration }}</td>
                                    <td class="report-view button-view" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">{{ $row->report_category }}</td>
                                    <td class="report-view button-view" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">
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
                                        @endif
                                    </td>
                                    <td class="report-view button-view" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">{{ $row->last_name.', '.$row->first_name.(($row->middle_name === null) ? '' : ' '.$row->middle_name).(($row->suffix === null) ? '' : ' '.$row->suffix) }}</td>
                                    {{-- <td class="report-view button-view" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">{{ $college_names[$row->id]->name ?? '-' }}</td> --}}
                                    {{-- <td class="report-view button-view" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">{{ $department_names[$row->id]->name }}</td> --}}
                                    <td class="report-view button-view text-center" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">
                                        @if ($row->report_category_id >= 1 && $row->report_category_id <= 8)
                                            @if ($row->researcher_approval === null)
                                                Receiving...
                                            @elseif ($row->researcher_approval == 0)
                                                <span class="text-danger font-weight-bold">Returned</span>
                                            @elseif ($row->researcher_approval == 1)
                                                <span class="text-success font-weight-bold">Received</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="report-view button-view text-center" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">
                                        @if (($row->report_category_id >= 9 && $row->report_category_id <= 14) || ($row->report_category_id >= 34 && $row->report_category_id <= 38) || $row->report_category_id == 23)
                                            @if ($row->extensionist_approval === null)
                                                Receiving...
                                            @elseif ($row->extensionist_approval == 0)
                                                <span class="text-danger font-weight-bold">Returned</span>
                                            @elseif ($row->extensionist_approval == 1)
                                                <span class="text-success font-weight-bold">Received</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="report-view button-view text-center" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">
                                        @if ($row->report_category_id >= 1 && $row->report_category_id <= 8)
                                            @if ($row->researcher_approval === null)
                                                -
                                            @elseif ($row->researcher_approval == 0)
                                                -
                                            @else
                                                @if ($row->chairperson_approval === null)
                                                    Receiving...
                                                @elseif ($row->chairperson_approval === 0)
                                                    <span class="text-danger font-weight-bold">Returned</span>
                                                @elseif ($row->chairperson_approval === 1)
                                                    <span class="text-success font-weight-bold">Viewed</span>
                                                @endif
                                            @endif
                                        @elseif (($row->report_category_id >= 9 && $row->report_category_id <= 14) || ($row->report_category_id >= 34 && $row->report_category_id <= 38) || $row->report_category_id == 23)
                                            @if ($row->extensionist_approval === null)
                                                -
                                            @elseif ($row->extensionist_approval == 0)
                                                -
                                            @else
                                                @if ($row->chairperson_approval === null)
                                                    Receiving...
                                                @elseif ($row->chairperson_approval === 0)
                                                    <span class="text-danger font-weight-bold">Returned</span>
                                                @elseif ($row->chairperson_approval === 1)
                                                    <span class="text-success font-weight-bold">Viewed</span>
                                                @endif
                                            @endif
                                        @else
                                            @if ($row->chairperson_approval === null && $department_names[$row->id] != '-')
                                                Receiving...
                                            @elseif($department_names[$row->id] == '-')
                                                N/A
                                            @elseif ($row->chairperson_approval === 0 && $department_names[$row->id] != '-')
                                                <span class="text-danger font-weight-bold">Returned</span>
                                            @elseif ($row->chairperson_approval === 1 && $department_names[$row->id] != '-')
                                                <span class="text-success font-weight-bold">Received</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="report-view button-view text-center" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">
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
                                                <span class="text-success font-weight-bold">Received</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="report-view button-view text-center" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">
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
                                                <span class="text-success font-weight-bold">Received</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="report-view button-view text-center" data-toggle="modal" data-target="#viewReport" data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}" data-report-category="{{ $row->report_category }}">
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
                                            @else
                                                -
                                            @endif
                                        @elseif ($row->report_category_id >= 9 && $row->report_category_id <= 14)
                                            @if (
                                                $row->extensionist_approval === 0 ||
                                                $row->chairperson_approval === 0 ||
                                                $row->dean_approval === 0 ||
                                                $row->sector_approval === 0 ||
                                                $row->ipqmso_approval === 0
                                            )
                                            <button class="button-deny action-remarks" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}"><i class="bi bi-chat-square-text" style="font-size: 1.25em;"></i> Remarks</button>
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

    <div class="modal fade" id="viewReport" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReportLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <table class="table table-sm table-borderless" id="columns_value_table">
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row justify-content-center">
                    <div class="col-md-11 h5 font-weight-bold">Documents</div>
                    <div class="col-md-11" id="data_documents">
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
                <h5 class="modal-title" id="viewDenyLabel">Reason for Returned Accomplishment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-borderless" style="width: 100%" id="view_deny_details_table">
                            <tr id="deny-detail-1">
                                <td class="font-weight-bold" style="width:50%">Returned By:</td>
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

    @include('reports.generate.index', ['data' => $department, 'source_type' => 'department', 'special_type' => ''])

    @push('scripts')
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $('#department_accomplishments_table').DataTable();

            $(document).on('click', '.button-view', function(){
                var catID = $(this).data('id');
                var link = $(this).data('url');

                var countColumns = 0;
                var url = "{{ url('reports/data/:id') }}";
				var newlink = url.replace(':id', catID);
				$.get(newlink, function (data){
                    Object.keys(data).forEach(function(k){
                        countColumns = countColumns + 1;
                        $('#columns_value_table').append('<tr id="row-'+countColumns+'" class="d-flex report-content"></tr>')
                        $('#row-'+countColumns).append('<td class="report-content font-weight-bold text-right" width="50%">'+k+':</td>');
                        $('#row-'+countColumns).append('<td class="report-content text-left">'+data[k]+'</td>');
                    });
                });
                var urldoc = "{{ url('reports/docs/:id') }}";
				var newlinkdoc = urldoc.replace(':id', catID);
				$.get(newlinkdoc, function (data) {
                    data.forEach(function (item){
                        var newlink = link.replace(':filename', item)
                        $('#data_documents').append('<a href="'+newlink+'" target="_blank" class="report-content h5 m-1 btn btn-primary">'+item+'<a/>');
                    });
                });

                var viewReport = document.getElementById('viewReport')
                var reportCategory = $(this).data('report-category')
                var modalTitle = viewReport.querySelector('.modal-title')
                modalTitle.textContent = reportCategory
            });

            $(document).on('click', '.button-deny', function () {
                var categoryID = $(this).data('id');

                var urldetails = "{{ url('reports/reject-details/:id') }}";
				var newlink2 = urldetails.replace(':id', categoryID);
				$.get(newlink2, function (data) {
                    var position = data.position_name;
                    var countColumns = 1;
                    var position_name = position.charAt(0).toUpperCase()+position.slice(1);
                    $('#deny-detail-'+countColumns).append('<td class="report-content">'+position_name+'</td>');
                    countColumns = countColumns + 1;
                    $('#deny-detail-'+countColumns).append('<td class="report-content">'+data.time+'</td>');
                    countColumns = countColumns + 1;
                    $('#deny-detail-'+countColumns).append('<td class="report-content">'+data.reason+'</td>');
                });
            });

            $('#viewReport').on('hidden.bs.modal', function(event) {
                $('.report-content').remove();
            });

            $('#viewDeny').on('hidden.bs.modal', function(event) {
                $('#deny-details').remove();
                $('.report-content').remove();
            });
            // auto hide alert
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);
        </script>
        <script>
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
                var link = "{{ url('reports/consolidate/department/reportYearFilter/:department/:year/:quarter') }}";
                var newLink = link.replace(':department', "{{$id}}").replace(':year', year_reported).replace(':quarter', quarter);
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
        <script>
            $("#exportLevel").click(function(){
                $('#ex_quar').val($('#quarterFilter').val());
                $('#ex_year').val($('#yearFilter').val());
                var form = document.getElementById('export_level_form');
                form.submit();
            });
        </script>
    @endpush
</x-app-layout>
