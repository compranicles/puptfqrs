<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('community-engagement.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Community Engagements</a>
                </p>
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-lightbulb-fill"></i> Tip: Press <strong>Enter</strong> <i class="bi bi-arrow-return-left"></i> key to add more item (applicable for elements that allow multiple inputs e.g., names, keywords, description of supporting documents, etc.).
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('community-engagement.store') }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $communityEngagementFields, 'colleges' => $colleges, 'colaccomp' => 1])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" id="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
        <script>
            $('#from').on('change', function () {
                $('#to').datepicker('setDate', $('#from').val());
                $('#to').datepicker('setStartDate', $('#from').val());
            });
        </script>
        <script>
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get("{{ url('document-upload/description/37') }}", function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>