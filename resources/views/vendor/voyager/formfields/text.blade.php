<input @if($row->required == 1) required @endif type="text" class="form-control" name="{{ $row->field }}"
       @if($row->disabled == 1) disabled @endif
        placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       {!! isBreadSlugAutoGenerator($options) !!}
       value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
