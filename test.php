<?php

define('ACCESS', true);

require_once 'function.php';

function zipDir1($name, $dir, $withDirName = false)
{
    $zip = new ZipArchive();

    if ($zip->open($name, ZipArchive::CREATE) !== true) {
        return false;
    }

    $currentDir = $dir;
    $dirName = basename($currentDir);
    $files = readDirectoryIterator($currentDir);

    foreach ($files as $file) {
        $path = $file->getPathname();
        $path = substr($path, strlen($currentDir) - strlen($path));
        $path = ltrim($path, '/');

        if ($withDirName) {
            $path = $dirName . '/' . $path;
        }

        if ($file->isDir()) {
            $zip->addEmptyDir($path);
        }

        if ($file->isFile()) {
            $zip->addFile($file, $path);
        }
    }

    $zip->close();
    
    return true;
}

var_dump(zipDir1('z-' . time() . '.zip' , __DIR__));
