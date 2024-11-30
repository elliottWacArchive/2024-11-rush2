<?php

function decodeTar($tarname)
{
    if (!file_exists($tarname)) {
        echo "ERREUR! : $tarname n'existe pas!\n";
        exit();
    }
    if (pathinfo($tarname, PATHINFO_EXTENSION) !== 'mytar') {
        echo "ERREUR! : $tarname n'est pas un fichier .mytar!\n";
        exit();
    }
    $tarcontent = file_get_contents($tarname);
    $tarcontent = json_decode(utf8_decode($tarcontent), true);
    return $tarcontent;
}

function makeFile($arr)
{
    foreach ($arr as $file) { //gere d'abord les dossiers
        if ($file['content'] === true) {
            if (!is_dir($file['path'])) {
                mkdir($file['path'], 0777, true);
            }
        }
    }

    foreach ($arr as $file) {
        if ($file['content'] !== true) {
            $dir = dirname($file['path']);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($file['path'], base64_decode($file['content']));
        }
    }
    echo "Extraction terminée!\n";
}

$liste_chemin = $argv;
array_shift($liste_chemin);
$name = 'output.mytar';

if ($argc < 2) {
    echo "ERREUR! : Renvoyez au moins un paramètre!\n";
    exit();
}

$tab = decodeTar($liste_chemin[0]);
makeFile($tab);
