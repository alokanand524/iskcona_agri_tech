<?php
if ($_FILES['file']) {
    $fileName = time() . '_' . $_FILES['file']['name'];
    $destination = 'uploads/' . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
        echo json_encode(['location' => $destination]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Upload failed']);
    }
}
