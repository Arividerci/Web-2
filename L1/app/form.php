<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $CSVFile = 'data.csv';

    $dataRow = [$name, $email];

    if (($file = fopen($CSVFile, 'a'))) {
        fputcsv($file, $dataRow);
        fclose($file);
        $message = 'Data saved';
    } else {
        $message = 'Error saving data';
    }
}

header("Location: index.php?message=" . urlencode($message));
exit;
