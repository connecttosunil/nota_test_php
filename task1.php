<?php

/**
 * Find files in a named as /data folder with names consisting of numbers and letters of the Latin alphabet,
 * having the .ixt extension, and display the names of these files ordered by name.
 *
 * @param string $folder The folder path to search for files.
 * @return array An array containing the names of the matching files.
 */
function findMatchingFilesInFolder($folder)
{
    // Regular expression pattern to match file names
    $pattern = '/^[a-zA-Z0-9]+\.ixt$/';

    // Get all files in the folder
    $files = scandir($folder);
    $matchingFiles = array();

    foreach ($files as $file) {
        if (preg_match($pattern, $file)) {
            $matchingFiles[] = $file;
        }
    }

    //Sort the matching files by name
    sort($matchingFiles);

    return $matchingFiles;
}

// Usage example
$dataFolder = '/data';
$matchingFiles = findMatchingFilesInFolder($dataFolder);

//Iterate all files and out put the name as well
foreach ($matchingFiles as $file) {
    echo $file . "\n";
}
