<?php

define('ACCESS', true);

include_once 'function.php';

if (!IS_LOGIN) {
    goURL('login.php');
}

$title = 'Tìm trong file';

require_once 'header.php';

echo '<div class="title">' . $title . '</div>';

if (
    $dir == null
    || !is_dir(processDirectory($dir))
) {
    echo '<div class="list"><span>Đường dẫn không tồn tại</span></div>
    <div class="title">Chức năng</div>
    <ul class="list">
        <li><img src="icon/list.png"/> <a href="index.php' . $pages['paramater_0'] . '">Danh sách</a></li>
    </ul>';
} else {
    $dir = processDirectory($dir);
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $case = isset($_POST['case']) ? (bool) $_POST['case'] : false;

    echo '<div class="list">
        <span class="bull">&bull; </span><span>' . printPath($dir, true) . '</span><hr/>
        <form method="post">
            Nội dung tìm kiếm:<br />
            <input type="text" name="search" value="' . htmlspecialchars($search) . '" style="width: 100%" /><br />
            <input type="checkbox" name="case" ' . ($case ? 'checked="checked"' : '') . ' />
            Phân biệt chữ hoa<br /><br />

            Loại trừ theo biểu thức:<br />
            <textarea name="exclude" rows="5" style="width: 60%">node_module/' . PHP_EOL . 'vendor/</textarea><br />
            <p style="font-size: small">
                Thư mục thì thêm / vào sau tên: <b>vendor/</b>
            </p>
            <input type="submit" name="submit" value="Tìm kiếm"/>
        </form>
        </div>';

    if (isset($_POST['submit'])) {
        $error = false;

        if (empty($search)) {
            echo $error = 'Chua nhap noi dung!';            
        }
        
        if ($error === false) {
            $files = readDirectoryIterator($dir);
            $files_search_count = 0;

            echo '<div class="list_line">';

            foreach ($files as $file) {
                if (!$file->isFile()) {
                    continue;
                }

                var_dump(ltrim(processDirectory($file->getPathname()), '.'));


                $fileObj = $file->openFile();
                $file_name = $fileObj->getFilename();
                $file_path = $fileObj->getPathname();
                $file_path = processDirectory($file_path);
                $file_path_sort = str_replace($dir, '', $file_path);
                $file_path_sort = ltrim($file_path_sort, '/');
                $file_have_search = false;

                while (!$fileObj->eof()) {
                    $line = $fileObj->fgets();
                    $line_number = $fileObj->key() + 1;

                    if ($case) {
                        $haveSearch = strpos($line, $search);
                    } else {
                        $haveSearch = stripos($line, $search);
                    }

                    if ($haveSearch !== false) {
                        if (!$file_have_search) {
                            $file_have_search = true;
                            $files_search_count += 1;

                            echo '<div id="line">
                                <div id="line_number_' . $line_number . '">';
                        }

                        if ($case) {
                            $line = str_replace(
                                htmlspecialchars($search),
                                '<span style="background-color: yellow">' . htmlspecialchars($search) . '</span>',
                                htmlspecialchars($line)
                            );
                        } else {
                            $line = preg_replace(
                                '/(' . preg_quote(htmlspecialchars($search)) . ')/i',
                                '<span style="background-color: yellow">${1}</span>',
                                htmlspecialchars($line)
                            );
                        }
                        
                        echo '<b>' . $line_number . ':</b> ' . $line . '<hr />';
                    }
                }
        
                if ($file_have_search) {
                    echo '</div><div>
                            <span id="line_number"><span>&bull; <a style="color: red" href="edit_text.php?dir=' . dirname($file_path) . '&name=' . $file_name . $pages['paramater_1'] . '">' . htmlspecialchars($file_path_sort) . '</a></span></span>
                            <span> | </span>
                        </div>
                        </div>';
                }
            }

            echo '</div>';

            echo '<div class="list">
                Tổng: <b>' . $files_search_count . '</b> file.
            </div>';
        }
    }

    echo '<div class="title">Chức năng</div>
    <ul class="list">
        <li><img src="icon/list.png"/> <a href="index.php?dir=' . $dirEncode . $pages['paramater_1'] . '">Danh sách</a></li>
    </ul>';
}

require_once 'footer.php';
