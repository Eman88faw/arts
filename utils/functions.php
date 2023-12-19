<?php

function getCurrentDate()
{
    $currentDateTime = date("Y-m-d H:i:s");

    // Get the microseconds
    $microseconds = microtime(true);

    // Format the microseconds with six decimal places
    $microsecondsFormatted = sprintf("%06d", ($microseconds - floor($microseconds)) * 1000000);

    // Concatenate the date and microseconds
    $result = $currentDateTime . '.' . $microsecondsFormatted;

    return $result;
}

?>