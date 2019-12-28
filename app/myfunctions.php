<?php

function alpha_spaces($value) {
    $message = '';
    if (strlen($value) == 0) {
        $message = 'Missing input';
    } else {
        $temp = str_replace(' ', '', $value);

        if (!ctype_alpha($temp)) {
            $message = 'Alpha characters and spaces only';
        }
    }
    return $message;
}

function email($value) {

    $message = '';

    if (strlen($value) == 0) {
        $message = 'Missing input';
    } else if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $message = 'Enter a valid email address';
    }
    return $message;
}

function mobile($value) {

    $message = '';
    if (strlen($value) == 0) {
        $message = 'Missing input';
    } else if (!ctype_digit($value)) {
        $message = 'Enter digits only';
    } else if (ctype_digit($value) && digitlen($value) != 10) {
        $message = 'Phone number must be 10 digits';
    }
    return $message;
}

function digitlen($number) {
    // Turn number into string
    $numlen = strlen((string) $number);
    return $numlen;
}

function validateDOB($value) {
    $message = '';

    if (strlen($value) == 0) {
        $message = 'Missing input';
    } else {

        $message = validateDate($value);

        if ($message == '') {

            $today = date('Y-m-d');
            $dob = date($value);
            $todayTime = strtotime($today);
            $dobTime2 = strtotime($dob);


            if ($todayTime < $dobTime2) {
                $message = 'Enter a valid birth date';
            }
        }
    }
    return $message;
}

function validateDate($value) {
    $message = '';

    if (strlen($value) == 0) {
        $message = 'Missing input';
    } else {
        list($year, $month, $day) = explode('-', $value);
        if (!checkdate($month, $day, $year)) {

            $message = 'Enter a valid date';
        }
    }
    return $message;
}
