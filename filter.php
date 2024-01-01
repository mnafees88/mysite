<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $csvFilePath = $_POST['csvFile'];
    $selectedDate = $_POST['selectedDate'];

    if (!file_exists($csvFilePath)) {
        echo "CSV file not found: $csvFilePath";
        exit;
    }

    $csvFile = fopen($csvFilePath, 'r');
    echo '<table class="table table-bordered mx-auto">';
    $firstRow = true;

    while (($data = fgetcsv($csvFile)) !== false) {
        if ($selectedDate && $data[0] !== $selectedDate) {
            continue;
        }

        echo '<tr>';
        foreach ($data as $value) {
            $cellTag = $firstRow ? 'th' : 'td';
            echo '<' . $cellTag . '>' . htmlspecialchars($value) . '</' . $cellTag . '>';
        }
        echo '</tr>';

        $firstRow = false;
    }

    echo '</table>';
    fclose($csvFile);
}
?>
