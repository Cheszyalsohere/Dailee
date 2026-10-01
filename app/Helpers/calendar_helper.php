<?php

if (!function_exists('generate_ics')) {
    function generate_ics($title, $start_time, $end_time, $description = '', $location = 'Kampus') {
        $dtstart = date('Ymd\THis', strtotime($start_time));
        $dtend   = date('Ymd\THis', strtotime($end_time ?: $start_time . ' +1 hour'));
        
        $ics  = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//Kampuskuu LifeRecap//ID\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . uniqid() . "@kampuskuu.local\r\n";
        $ics .= "DTSTAMP:" . date('Ymd\THis\Z') . "\r\n";
        $ics .= "DTSTART:" . $dtstart . "\r\n";
        $ics .= "DTEND:" . $dtend . "\r\n";
        $ics .= "SUMMARY:" . addcslashes($title, ",;") . "\r\n";
        $ics .= "DESCRIPTION:" . addcslashes($description, ",;") . "\r\n";
        $ics .= "LOCATION:" . addcslashes($location, ",;") . "\r\n";
        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        return $ics;
    }
}