<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class MyMultiSelect extends AbstractHandler
{
    protected $codename = 'my_multi_select';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('formfields.my-multi-select', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}
