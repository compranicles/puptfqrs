<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Details') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->research_code])
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Research Copyrighted</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="dropdown">
                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Options
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="{white-space: nowrap; }}">
                                    @switch($research->status_name)
                                            @case('New Commitment') @case('Ongoing')
                                                <a class="dropdown-item" id="to-complete" href="{{ route('research.completed.create', $research->research_code) }}">Mark as Completed</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed')
                                                <a class="dropdown-item" id="to-publish" href="{{ route('research.publication', $research->research_code ) }}">Mark as Published</a>
                                                <a class="dropdown-item" id="to-present" href="{{ route('research.presentation', $research->research_code ) }}">Mark as Presented</a>
                                                <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Add Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Edit Completed Research</a>
                                                @break
                                            @case('Published')
                                                <a class="dropdown-item" id="to-present" href="{{ route('research.presentation', $research->research_code ) }}">Mark as Presented</a>
                                                <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Add Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.citation.create', $research->research_code) }}">Add Citation</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Edit Completed Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Edit Publication</a>
                                                @break
                                            @case('Presented')
                                                
                                                <a class="dropdown-item" id="to-publish" href="{{ route('research.publication', $research->research_code ) }}">Mark as Published</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Add Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Edit Completed Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Edit Presentation</a>
                                                @break
                                            @case('Presented & Published')
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Add Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.citation.create', $research->research_code) }}">Add Citation</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Edit Completed Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Edit Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.presentation', $research->research_code) }}">Edit Presentation</a>
                                                @break
                                            @case('Deferred')
                                                @break
                                            @default
                                                
                                        @endswitch
                                        <a class="dropdown-item" href="{{ route('research.edit', $research->research_code) }}">Edit Research Info</a>
                                        <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <fieldset id="research">
                            @include('research.form-view', ['formFields' => $researchFields, 'value' => $values,])
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 id="textHome" style="color:maroon">Supporting Documents</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 style="color:maroon"><i class="far fa-file-alt mr-2"></i>Documents</h6>
                                <div class="row">
                                    @if (count($researchDocuments) > 0)
                                        @foreach ($researchDocuments as $document)
                                            @if(preg_match_all('/application\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                <div class="col-md-12 mb-3">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <div class="embed-responsive embed-responsive-1by1">
                                                                        <iframe  src="{{ route('document.view', $document['filename']) }}" width="100%" height="500px"></iframe>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-md-4 offset-md-4">
                                            <h6 class="text-center">No Documents Attached</h6>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 style="color:maroon"><i class="far fa-image mr-2"></i>Images</h6>
                                <div class="row">
                                    @if(count($researchDocuments) > 0)
                                        @foreach ($researchDocuments as $document)
                                            @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                <div class="col-md-6 mb-3">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}">
                                                            <img src="{{ route('document.display', $document['filename']) }}" class="card-img-top img-resize"/>
                                                        </a>
                                                        
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-md-4 offset-md-4">
                                            <h6 class="text-center">No Documents Attached</h6>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Form Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">Are you sure you want to delete this research?</h5>
                    <form action="{{ route('research.destroy', $research->research_code) }}" method="POST">
                        @csrf
                        @method('delete')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger mb-2 mr-2">Delete</button>
                </form>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function() {
            $('#research').prop('disabled', true);
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