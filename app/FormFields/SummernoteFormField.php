<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class SummernoteFormField extends AbstractHandler
{
    protected $codename = 'summernote';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('formfields.summernote', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}
