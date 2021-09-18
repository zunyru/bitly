<?php
    $list = isset($_POST['_list']) ? json_decode($_POST['_list'], true) : null;

    print_r($list);