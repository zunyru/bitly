<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class SlugFormField extends AbstractHandler
{
    protected $codename = 'slug';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('formfields.slug', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}
