<?php
// Specify the path to your CSV file
$csvFilePath = 'file.csv';

// Function to convert CSV data to an HTML table
function csvToHtmlTable($csvFilePath)
{
    if (!file_exists($csvFilePath)) {
        die("CSV file not found");
    }

    // Read CSV file
    $csvFile = fopen($csvFilePath, 'r');

    echo '<table border="1">';
    while (($data = fgetcsv($csvFile)) !== false) {
        echo '<tr>';
        foreach ($data as $value) {
            echo '<td>' . htmlspecialchars($value) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';

    fclose($csvFile);
}

// Display the HTML table
csvToHtmlTable($csvFilePath);
?>
