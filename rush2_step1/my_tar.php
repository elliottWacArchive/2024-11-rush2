<?php

function makeTar($name)
{
    fopen($name, 'w+');
}

function listFiles($arr)
{
    $file_tree = [];
    foreach ($arr as $val) {
        if (is_dir($val)) {
            array_push($file_tree, $val);
            $file_tree = scanFolder($val . '/', $file_tree);
        } elseif (file_exists($val)) {
            array_push($file_tree, $val); // possible changement
        } else {
            echo "ERREUR! : $val n'existe pas!\n";
            exit();
        }
    }
    return $file_tree;
}

function scanFolder($dir, &$tree)
{
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while ((($file = readdir($dh)) !== false)) {
                if ($file != '.' && $file != '..') {
                    if (!is_dir($dir . $file)) {
                        array_push($tree, $dir . $file);
                    } else {
                        echo "rec\n";
                        array_push($tree, $dir . $file);
                        scanFolder($dir . $file . '/', $tree);
                    }
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
    makeTar($name);
    file_put_contents($name, utf8_encode(json_encode($arr)));
    echo "Le fichier $name a été créé avec succès!\n";
}

$liste_chemin = $argv;
array_shift($liste_chemin);
$name = 'output.mytar';

if ($argc < 2) {
    echo "ERREUR! : Renvoyez au moins un paramètre!\n";
    exit();
}

$file_tree = listFiles($liste_chemin);
$tree = makeTree($file_tree);
// var_dump($file_tree);
addTar($tree, $name);
