<?php


define('LOGIN', true);
define('ACCESS', true);

require_once 'function.php';

class Zip extends ZipArchive {
    public function add($path, $relative = null)
    {
        if (!file_exists($path)) {
            return false;
        }
        
        $file = new SplFileInfo($path);
        $path = $file->getPathname();
        $pathRelative = $path;

        if ($relative) {
            $pathRelative = substr($path, strlen($relative));
        }
    
        if ($file->isFile()) {
            $this->addFile($path, $pathRelative);
        }
        
        if ($file->isDir()) {
            $this->addEmptyDir($pathRelative);
        }
    }
}

$dir = '/home/samndcbe/public_html';
$entrys = [
    'nodejs16',
    'nodejs18',
    '.htaccess'
];

$zip = new Zip();
if ($zip->open($dir . '/zip-' . time() . '.zip', ZipArchive::CREATE) !== true) {
    return false;
}

foreach ($entrys as $entry) {
    $zip->add("$dir/$entry", $dir);
}

$zip->close();
