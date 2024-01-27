<?php
    $dirFixer = __DIR__ . '/lib/format';
    $tempFile = $dirFixer .'/fixer.txt';   
    $data = array(
      'highlight' => '',
      'error' => 'Không thành công! Chỉnh dành cho code php'
    );
    $content = isset($_POST['content']) ? $_POST['content'] : null;
    if(isset($content) && isset($_POST['requestApi'])){       
        file_put_contents($tempFile, $content);
        if(exec('cd '. $dirFixer .' && vendor/bin/php-cs-fixer fix fixer.txt')){
          $data['highlight'] = file_get_contents($tempFile);
          $data['error'] = '';
        } 
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);