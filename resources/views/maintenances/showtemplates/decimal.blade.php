<tr>
    <th>{{ $fieldInfo->label }}</th>
    <td><span id="currency"></span> {{ $value }}</td>
</tr>

@push('scripts')
    <script>
        $('#currency').ready(function (){
            $.get("{{ route('currency.name', $currency) }}", function (data){
                $('#currency').html(data);
            });
        });
    </script>
@endpush