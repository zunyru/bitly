<input type="text" class="form-control date-time datepicker" name="{{ $row->field }}"
    placeholder="{{ $row->getTranslatedAttribute('display_name') }}"
    value="@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->format('Y-m-d') }}@else{{old($row->field)}}@endif">
@push('custom-scripts')
<script>
    var datetime= `@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->format('Y-m-d') }}@else{{old($row->field)}}@endif`;
    $('.date-time').data("DateTimePicker").date(datetime);
</script>
@endpush
