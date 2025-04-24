<?php

if (!function_exists('covertdate')) {
    function covertdate($data){
        $dateString = $data; // '2024-04-01'
        $date = new DateTime($dateString);
        $formattedDate = $date->format('d-m-Y'); // '01-04-2024'

        return $formattedDate;
    }
}
