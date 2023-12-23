<?php

define('ACCESS', true);

include_once 'function.php';

if (!IS_LOGIN) {
    goURL('login.php');
}

$title = 'Sửa tập tin nâng cao';

include_once 'header.php';

echo '<div class="title">' . $title . '</div>';

if ($dir == null || $name == null || !is_file(processDirectory($dir . '/' . $name))) {
    echo '<div class="list"><span>Đường dẫn không tồn tại</span></div>
        <div class="title">Chức năng</div>
        <ul class="list">
            <li><img src="icon/list.png"/> <a href="index.php' . $pages['paramater_0'] . '">Danh sách</a></li>
        </ul>';
} else if (!isFormatText($name) && !isFormatUnknown($name)) {
    echo '<div class="list"><span>Tập tin này không phải dạng văn bản</span></div>
        <div class="title">Chức năng</div>
        <ul class="list">
            <li><img src="icon/list.png"/> <a href="index.php?dir=' . $dirEncode . $pages['paramater_1'] . '">Danh sách</a></li>
        </ul>';
} else {
    $dir = processDirectory($dir);
    $path = $dir . '/' . $name;
    $content = file_get_contents($path);
    $actionEdit = 'edit_code_api.php?dir=' . $dirEncode . '&name=' . $name;
    $fileExt = getFormat($name);

    $codeLang = 'text';
    $codeType = [
        'text' => 'Text',
        'php' => 'PHP',
        'javascript' => 'JavaScript',
    ];

    if (array_key_exists($fileExt, $codeType)) {
        $codeLang = $fileExt;
    }

    echo '<div class="list">
        <span class="bull">&bull; </span><span>' . printPath($dir, true) . '</span><hr/>
        <div class="ellipsis break-word">
            <span class="bull">&bull;</span>
            Tập tin:
            <strong class="file_name_edit">' . $name . '</strong>
            <hr />
            <div class="code_action">
                Chọn loại code =)):
                <select id="code_lang">';

    foreach ($codeType as $cType => $cValue) {
        $cSeleted = $codeLang == $cType ? 'selected="selected"' : '';
        echo "<option {$cSeleted} value=\"{$cType}\">{$cValue}</option>";
    }

    echo '</select>
            </div>        
            <p style="white-space: normal">Nếu không thấy nội dung file, vui lòng không chỉnh sửa trên web!</p>
            <hr/>
        </div>

        <style type="text/css" media="screen">
            #editor {
                width: 100%;
                height: 500px;
                font-size: 14px;
            }

            .ace_scroller, .ace_gutter {
                padding-top: 10px;
            }
        </style>

        <div id="editor" contenteditable="true">' . htmlspecialchars($content) . '</div>

        <div class="input_action">
            <form id="code_form" action="javascript:void(0)">
                <input type="submit" value="Lưu lại" />
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/ace-builds@1.32.2/src-min-noconflict/ace.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/ace-builds@1.32.2/css/ace.min.css" rel="stylesheet">
        
        <script>
            var codeLang = "' . $codeLang . '";
            var editor = ace.edit("editor");
            
            editor.setShowPrintMargin(false);
            editor.setTheme("ace/theme/one_dark");
            editor.session.setMode("ace/mode/" + codeLang);
            
            var codeLangElement = document.getElementById("code_lang");
            codeLangElement.addEventListener("change", function () {
                var mode = codeLangElement.value;
                editor.session.setMode("ace/mode/" + mode);
            });
            
            var codeFormElement = document.getElementById("code_form");
            codeFormElement.addEventListener("submit", function (event) {
                var data = new FormData();
                data.append("requestApi", 1);
                data.append("content", editor.getValue());

                fetch("' . $actionEdit . '", {
                    method: "POST",
                    body: data,
                    cache: "no-cache"
                }).then(function (response) {
                    if (response.status != 200) {
                        alert("Lỗi kết nối!");
                        return false;
                    } 
                    
                    return response.json();
                }).then((data) => alert(data.message));

                event.preventDefault();
                return false;
            });

        </script>

        </div>
        
        <div class="title">Chức năng</div>
        <ul class="list">
            <li><img src="icon/info.png"/> <a href="file.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Thông tin</a></li>
        </ul>';
}

include_once 'footer.php';

