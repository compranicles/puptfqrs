<x-app-layout>
    <x-slot name="header">
        @include('reports.navigation', compact('roles', 'departments', 'colleges', 'sectors', 'departmentsResearch','departmentsExtension'))
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Review College Extensions</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    {{ $message }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style="display: none; padding-top: 20px;" id="actionButtons">
                                <button id="acceptButton" data-toggle="modal" data-target="#selectApprove" class="btn btn-primary mr-2"><i class="bi bi-check2"></i> Accept</button>
                                <button id="denyButton" data-toggle="modal" data-target="#selectDeny" class="btn btn-secondary"><i class="bi bi-slash-circle"></i> Return</a>
                            </div>
                            <!-- <div class="col-md-6 ml-auto">
                                <div class="form-group">
                                    <label class="mt-2 mr-2" for="employeeFilter">Employee:</label>
                                    <select id="employeeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                    </select>
                                </div>
                            </div> -->
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover" id="to_review_table">
                                        <thead>
                                            <tr>
                                                <th class="text-center"><input type="checkbox" id="select-all"></th>
                                                <th></th>
                                                <th></th>
                                                <th>Accomplishment Report</th>
                                                <th>Title</th>
                                                <th>Employee</th>
                                                {{-- <th>College/Branch/Campus/Office</th> --}}
                                                <th>Department/Section</th>
                                                <th>Report Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reportsToReview as $row)
                                                <tr role="button">
                                                    <td class="text-center"><input type="checkbox" class="select-box" data-id="{{ $row->id }}"></td>
                                                    <td class="button-view text-center" data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('extensionist.accept', ':id') }}" data-deny="{{ route('extensionist.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport" data-report-category="{{ $row->report_category }}"><i class="bi bi-three-dots-vertical"></i></td>
                                                    <td class="button-view text-center" data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('extensionist.accept', ':id') }}" data-deny="{{ route('extensionist.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport" data-report-category="{{ $row->report_category }}">{{ $loop->iteration }}</td>
                                                    <td class="button-view" data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('extensionist.accept', ':id') }}" data-deny="{{ route('extensionist.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport" data-report-category="{{ $row->report_category }}">{{ $row->report_category }}</td>
                                                    <td class="button-view" data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('extensionist.accept', ':id') }}" data-deny="{{ route('extensionist.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport" data-report-category="{{ $row->report_category }}">
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
                                                        @elseif (isset($row->report_details->organization))
                                                            {{ $row->report_details->organization }}
                                                        @elseif (isset($row->report_details->degree))
                                                            {{ $row->report_details->degree }}
                                                        @endif
                                                    </td>
                                                    <td class="button-view" data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('extensionist.accept', ':id') }}" data-deny="{{ route('extensionist.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport" data-report-category="{{ $row->report_category }}">{{ $row->last_name.', '.$row->first_name.(($row->middle_name == null) ? '' : ', '.' '.$row->middle_name).(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                                    {{-- <td class="button-view" data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('extensionist.accept', ':id') }}" data-deny="{{ route('extensionist.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport" data-report-category="{{ $row->report_category }}">{{ $college_names[$row->id]->name ?? '-' }}</td> --}}
                                                    <td class="button-view" data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('extensionist.accept', ':id') }}" data-deny="{{ route('extensionist.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport" data-report-category="{{ $row->report_category }}">{{ $department_names[$row->id]->name ?? '-' }}</td>
                                                    <td class="button-view" data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('extensionist.accept', ':id') }}" data-deny="{{ route('extensionist.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport" data-report-category="{{ $row->report_category }}">{{ date( "F j, Y, g:i a", strtotime($row->created_at)) }}</td>
                                                </tr>
                                            @endforeach
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
                    <div class="col-md-11 h5 font-weight-bold">Documents:</div>
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
            </div>
            <div class="modal-footer">
                <div class="ml-auto" id="review_btn_reject">
                </div>
                <div class="ml-2" id="review_btn_accept">
                </div>
                <span style="display: inline-block;
                    border-left: 1px solid #ccc;
                    margin: 0px 20px 0px 20px;;
                    height: 30px;"></span>
                <div class="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="selectDeny" tabindex="-1" aria-labelledby="selectDenyLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectDenyLabel">Return Selected</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">Are you sure you want to  <span class="text-danger font-weight-bold">RETURN</span> selected?</div>
                </div>
                <form action="{{ route('extensionist.deny-select') }}" method="POST">
                    @csrf
                    @foreach ($reportsToReview as $row)
                        <input class="report-{{ $row->id }}" type="hidden" value="{{ $row->id }}" name="report_id[]" disabled>
                    @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger mb-2 mr-2">YES</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="selectApprove" tabindex="-1" aria-labelledby="selectApproveLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectApproveLabel">Receive Selected</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">Are you sure you want to <span class="text-success font-weight-bold">RECEIVE</span> selected?</div>
                </div>
                <form action="{{ route('extensionist.accept-select') }}" method="POST">
                    @csrf
                    @foreach ($reportsToReview as $row)
                        <input class="report-{{ $row->id }}" type="hidden" value="{{ $row->id }}" name="report_id[]" disabled>
                    @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success mb-2 mr-2">YES</button>
                </form>
            <div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#to_review_table').DataTable();
        // var table = $('#to_review_table').DataTable({
        //     order: [[1, 'asc']],
        //     columnDefs: [ {
        //         targets: 0,
        //         orderable: false
        //     } ],
        //     initComplete: function () {
        //     this.api().columns(4).every( function () {
        //         var column = this;
        //         var select = $('#employeeFilter')
        //             .on( 'change', function () {
        //                 var val = $.fn.dataTable.util.escapeRegex(
        //                     $(this).val()
        //                 );

        //                 column
        //                     .search( val ? '^'+val+'$' : '', true, false )
        //                     .draw();
        //             } );

        //         column.data().unique().sort().each( function ( d, j ) {
        //             select.append( '<option value="'+d+'">'+d+'</option>' )
        //         } );
        //     });
        //     }
        // });
    </script>
    <script>
        $('#select-all').on('click', function(){
            if(this.checked){
                $('.select-box').prop('checked', true);
                $('.select-box').each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('.report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('.report-'+inputId).attr('disabled', true);
                    }
                });
            }
            else{
                $('.select-box').prop('checked', false);
                $('.select-box').each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('.report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('.report-'+inputId).attr('disabled', true);
                    }
                });
            }

            var allChecked = 0;
            $(".select-box").each(function(index, element){
                if(this.checked){
                    allChecked++;
                }
            });
            if(allChecked == 0){
                $('#select-all').prop('checked', false);
                $('#actionButtons').hide();

            }
            else{

                $('#actionButtons').show();
            }
        });
        $('.select-box').on('click', function(){
            var inputId = $(this).data('id');

            if(this.checked){
                $('.report-'+inputId).removeAttr('disabled');
            }
            else{
                $('.report-'+inputId).attr('disabled', true);
            }

            var allChecked = 0;
            var flag = true;
            $(".select-box").each(function(index, element){
                if(this.checked){
                    allChecked++;
                }
                else{
                    flag = false;
                }
            });
            if(allChecked == 0){
                $('#select-all').prop('checked', false);
                $('#actionButtons').hide();

            }
            else{

                $('#actionButtons').show();
            }
            if(flag == true){
                $('#select-all').prop('checked', true);
            }else{
                $('#select-all').prop('checked', false);
            }


        });

        $(document).on('click', '.button-view', function(event){
            event.stopPropagation();
            var catID = $(this).data('id');
            var link = $(this).data('url');
            var accept = $(this).data('accept');
            var deny = $(this).data('deny');
            var countColumns = 0;

            var url = "{{ url('/reports/data/:id/') }}";
			var newlink = url.replace(':id', catID);
			$.get(newlink, function (data){
                Object.keys(data).forEach(function(k){
                    countColumns = countColumns + 1;
                    $('#columns_value_table').append('<tr id="row-'+countColumns+'" class="report-content d-flex"></tr>')
                    $('#row-'+countColumns).append('<td class="report-content font-weight-bold text-right" width="50%">'+k+':</td>');
                    $('#row-'+countColumns).append('<td class="report-content text-left">'+data[k]+'</td>');
                });
            });
            var urldoc = "{{ url('/reports/docs/:id/') }}";
			var newlinkdoc = urldoc.replace(':id', catID);
			$.get(newlinkdoc, function (data) {
                if(data == false){
                    $('#data_documents').append('<a class="report-content btn-link text-dark">No Document Attached</a>');
                }
                else{
                    data.forEach(function (item){
                        var newlink = link.replace(':filename', item)
                            $('#data_documents').append('<a href="'+newlink+'" target="_blank" class="report-content btn btn-dark m-1">'+item+'</a>');
                    });
                }
            });

            $('#review_btn_accept').append('<a href="'+accept.replace(':id', catID)+'" class="btn btn-primary report-content"><i class="bi bi-check2"></i> Accept</a>');
            $('#review_btn_reject').append('<a href="'+deny.replace(':id', catID)+'" class="btn btn-secondary report-content"><i class="bi bi-slash-circle"></i> Return</a>');

            var viewReport = document.getElementById('viewReport')
            var reportCategory = $(this).data('report-category')
            var modalTitle = viewReport.querySelector('.modal-title')
            modalTitle.textContent = reportCategory
        });


        $('#viewReport').on('hidden.bs.modal', function(event) {
            $('.report-content').remove();
        });

        $(function () {
            var allChecked = 0;
            $(".select-box").each(function(index, element){
                if(this.checked){
                    allChecked++;
                }
            });
            if(allChecked == 0){
                $('#select-all').prop('checked', false);
                $('#actionButtons').hide();


            }
            else{

                $('#actionButtons').show();
            }

            // var empIndex = 0;
            // $("#to_review_table th").each(function (i) {
            //     if ($($(this)).html() == "Employee") {
            //         empIndex = i; return false;

            //     }
            // });

            // $.fn.dataTable.ext.search.push(
            //     function (settings, data, dataIndex) {
            //         var selectedItem = $('#employeeFilter').val()
            //         var employee = data[empIndex];
            //         if (selectedItem === "" || employee.includes(selectedItem)) {
            //             return true;
            //         }
            //         return false;
            //     }
            // );

            // $("#employeeFilter").change(function (e) {
            //     table.draw();
            // });

            // table.draw();
        });
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);
    </script>

@endpush

</x-app-layout>
