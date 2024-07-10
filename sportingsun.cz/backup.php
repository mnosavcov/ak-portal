<?php

// Umístění, kam se uloží přijatý soubor
$uploadDir = dirname(dirname(__DIR__)) . '/backups-pvtrusted-cz/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo "success";
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "Upload failed with error code " . $file['error'];
    }
} else {
    echo "No file uploaded or invalid request.";
}
