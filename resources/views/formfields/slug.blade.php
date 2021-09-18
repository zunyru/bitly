<input  required  type="text" class="form-control " name="{{ $row->field }}" id="slug"  data-db="{{ $dataType->name }}"
placeholder="{{ old($row->field, $options->placeholder ?? $row->display_name) }}"
{!! isBreadSlugAutoGenerator($options) !!}
value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
<label id="{{ $row->field }}-error-dup" class="dup-error" for="{{ $row->field }}" style="display: none;color:red">{{ $row->display_name." ซ้ำ" }}</label>

@push('custom-scripts')
@include('javascript.slug-js');
@endpush       
