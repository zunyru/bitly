@php
$dataTypeContent->{$row->field} = json_decode($dataTypeContent->{$row->field});
@endphp
<select class="form-control select2" name="{{ $row->field }}[]" multiple="multiple">
    @if(!is_null($dataTypeContent->{$row->field}))
    @foreach ($dataTypeContent->{$row->field} as $item)
    <option value="{{$item}}" selected>{{$item}}</option>
    @endforeach
    @endif
</select>
