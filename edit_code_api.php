<?php

define('ACCESS', true);

include_once 'function.php';

if (!IS_LOGIN) {
    goURL('login.php');
}

$data = [
    'status' => false,
    'message' => 'error'
];

if (isset($_POST['requestApi'])) {
    if ($dir == null || $name == null || !is_file(processDirectory($dir . '/' . $name))) {
        $data['message'] = 'Đường dẫn không tồn tại';
    } else if (!isFormatText($name) && !isFormatUnknown($name)) {
        $data['message'] = 'Tập tin này không phải dạng văn bản';
    } else {
        $dir = processDirectory($dir);
        $path = $dir . '/' . $name;
    
        if (!isset($_POST['content']) || empty($_POST['content'])) {
            $data['message'] = 'Chưa nhập nội dung';
        } else {
            $content = $_POST['content'];
    
            if (file_put_contents($path, $content)) {
                $data['status'] = true;
                $data['message'] = 'Lưu lại thành công';
            } else {
                $data['message'] = 'Lưu lại thất bại';
            }
        }
    }
}

@ob_end_clean();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);