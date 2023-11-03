<?php

/**
 * Downloads the page from the given URL and extracts headings, abstracts, pictures, and links from the page's sections.
 * Saves the extracted data in the `wiki_sections` table.
 *
 * @param string $url The URL of the page to download and extract data from.
 * @return bool True if the data is successfully saved in the `wiki_sections` table, false otherwise.
 */
function downloadAndExtractDataFromAUrl($url)
{
    // Download the page content
    $pageContent = file_get_contents($url);

    if ($pageContent === false) {
        return false;
    }

    // Extract headings, abstracts, pictures, and links from the page's sections
    $pattern = '/<h[2-6]>(.*?)<\/h[2-6]>.*?<p>(.*?)<\/p>.*?<img.*?src="(.*?)".*?>.*?<a.*?href="(.*?)".*?>/is';
    preg_match_all($pattern, $pageContent, $matches, PREG_SET_ORDER);

    // Save the extracted data in the `wiki_sections` table
    $dbHost = 'localhost';
    $dbName = 'local_wiki';
    $dbUser = 'root';
    $dbPass = 'sunil';

    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

    foreach ($matches as $match) {
        $heading = $match[1];
        $abstract = $match[2];
        $picture = $match[3];
        $link = $match[4];

        $query = "INSERT INTO wiki_sections (heading, abstract, picture, link) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$heading, $abstract, $picture, $link]);
    }

    return true;
}

// Here is url to extract data from
$url = 'https://www.wikipedia.org/';
$result = downloadAndExtractDataFromAUrl($url);

if ($result) {
    echo "Data saved successfully in the wiki_sections table.";
} else {
    echo "Failed to download and extract data from Url.".$url;
}
