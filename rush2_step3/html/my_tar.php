<?php

function listFiles($paths)
{
    $tree = [];
    foreach ($paths as $path) {
        if (is_file($path)) {
            $tree[] = $path;
        } elseif (is_dir($path)) {
            $dh = opendir($path);
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $tree = array_merge($tree, listFiles([$path . '/' . $file]));
                }
            }
            closedir($dh);
        }
    }
    return $tree;
}

function makeTree($arr)
{
    $tree = [];
    $i = 0;
    foreach ($arr as $file) {
        $tmp = explode('/', $file); // cas ou il y a un chemin pour afficher seulement le nom
        $fileName = end($tmp);
        $filePath = $file;
        if (is_file($file)) {
            $fileContent = base64_encode(file_get_contents($file));
        } else {
            $fileContent = true;
        }
        $tree[$i] = array('path' => $filePath, 'name' => $fileName, 'content' => $fileContent);
        $i++;
    }
    return $tree;
}

function addTar($arr, $name)
{
    file_put_contents($name, utf8_encode(json_encode($arr)));
    echo "Le fichier $name a été créé avec succès!\n";
}

$liste_chemin = array_slice($argv, 1, -1);
$name = end($argv);

if (empty($liste_chemin)) {
    echo "ERREUR! : Renvoyez au moins un paramètre!\n";
    exit();
}

print_r($liste_chemin);

$file_tree = listFiles($liste_chemin);

$tree = makeTree($file_tree);

addTar($tree, $name);
