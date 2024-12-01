<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFiles = [];
    foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['files']['name'][$key]);
        $filePath = $uploadDir . $fileName;
        if (move_uploaded_file($tmpName, $filePath)) {
            $uploadedFiles[] = $filePath;
        }
    }

    if (!empty($uploadedFiles)) {
        $outputFile = 'output.mytar';
        $command = 'php my_tar.php ' . implode(' ', $uploadedFiles) . ' ' . $outputFile;
        exec($command, $output, $return_var);
        if ($return_var === 0) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $outputFile . '"');
            readfile($outputFile);
            exit();
        } else {
            echo "Erreur lors de la création du fichier tar!";
        }
    } else {
        echo "Erreur lors du téléchargement des fichiers!";
    }
} else {
    echo "Méthode de requête non supportée!";
}
