<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class DateTime extends AbstractHandler
{
    protected $codename = 'my_date_time';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('formfields.my-date-time', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}
