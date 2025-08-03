<?php
// upload-image.php

if (isset($_FILES['upload'])) {
    $file = $_FILES['upload'];
    $fileName = time() . '_' . basename($file['name']);
    $uploadDir = '../uploads/';
    $uploadPath = $uploadDir . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $url = $uploadPath;
        echo json_encode([
            "uploaded" => 1,
            "fileName" => $fileName,
            "url" => $url
        ]);
    } else {
        echo json_encode([
            "uploaded" => 0,
            "error" => [ "message" => "Upload failed." ]
        ]);
    }
}
