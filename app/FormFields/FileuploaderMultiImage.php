<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class FileuploaderMultiImage extends AbstractHandler
{
    protected $codename = 'fileuploader_multi_image';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('formfields.fileuploader-multi-image', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}
