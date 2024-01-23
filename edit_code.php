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

    echo '</select><br />
            <input type="checkbox" id="code_wrap" />
            Wrap<br />
            </div>

            <p style="white-space: normal">Nếu không thấy nội dung file, vui lòng không chỉnh sửa trên web!</p>
            <hr/>
        </div>

        <form id="code_form" action="javascript:void(0)">
            <div>
                <textarea id="editor" class="box_edit">' . htmlspecialchars($content) . '</textarea>
            </div>
            <div class="input_action">
                <input type="submit" value="Lưu lại" />
            </div>
        </form>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js" integrity="sha512-OeZ4Yrb/W7d2W4rAMOO0HQ9Ro/aWLtpW9BUSR2UOWnSV2hprXLkkYnnCGc9NeLUxxE4ZG7zN16UuT1Elqq8Opg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/darcula.min.css" integrity="sha512-kqCOYFDdyQF4JM8RddA6rMBi9oaLdR0aEACdB95Xl1EgaBhaXMIe8T4uxmPitfq4qRmHqo+nBU2d1l+M4zUx1g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/selection/active-line.min.js" integrity="sha512-0sDhEPgX5DsfNcL5ty4kP6tR8H2vPkn40GwA0RYTshkbksURAlsRVnG4ECPPBQh7ZYU6S3rGvp5uhlGQUNrcmA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js" integrity="sha512-HN6cn6mIWeFJFwRN9yetDAMSh+AK9myHF1X9GlSlKmThaat65342Yw8wL7ITuaJnPioG0SYG09gy0qd5+s777w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js" integrity="sha512-LarNmzVokUmcA7aUDtqZ6oTS+YXmUKzpGdm8DxC46A6AHu+PQiYCUlwEGWidjVYMo/QXZMFMIadZtrkfApYp/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js" integrity="sha512-rQImvJlBa8MV1Tl1SXR5zD2bWfmgCEIzTieFegGg89AAt7j/NBEe50M5CqYQJnRwtkjKMmuYgHBqtD1Ubbk5ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/clike/clike.min.js" integrity="sha512-jcF10R6LSoLddMx32eEitiBfJ8icHBobh0Z7fwVewrKmNBBGM0B09oG3yxxnkIYwilUsXBbIcRN5jfmc6vSt9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js" integrity="sha512-Cbz+kvn+l5pi5HfXsEB/FYgZVKjGIhOgYNBwj4W2IHP2y8r3AdyDCQRnEUqIQ+6aJjygKPTyaNT2eIihaykJlw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/php/php.min.js" integrity="sha512-jZGz5n9AVTuQGhKTL0QzOm6bxxIQjaSbins+vD3OIdI7mtnmYE6h/L+UBGIp/SssLggbkxRzp9XkQNA4AyjFBw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <style type="text/css" media="screen">
            @media (min-width: 240px) {
                .CodeMirror {
                    height: 320px;
                }
            }
            
            @media (min-width: 320px) {
                .CodeMirror {
                    height: 480px;
                }
            }
            
            @media (min-width: 480px) {
                .CodeMirror {
                    height: 640px;
                }
            }
            
            @media (min-width: 640px) {
                .CodeMirror {
                    height: 720px;
                }
            }
        </style>
        
        <script>
            var codeLang = "' . $codeLang . '";
            const editorElement = document.getElementById("editor");
            var editor = CodeMirror.fromTextArea(editorElement, {
                mode: codeLang,
                theme: "darcula",
                lineNumbers: true,
                lineWrapping: false,
                fixedGutter: false,
                styleActiveLine: true,
            });
            
            var codeLangElement = document.getElementById("code_lang");
            codeLangElement.addEventListener("change", function () {
                var mode = codeLangElement.value;
                editor.setOption("mode", mode);
            });
            
            var codeWrapElement = document.getElementById("code_wrap");
            codeWrapElement.addEventListener("change", function () {
                editor.setOption("lineWrapping", codeWrapElement.checked);
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
            <li><img src="icon/info.png"/> <a href="file.php?dir='      . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Thông tin</a></li>
            <li><img src="icon/edit.png"/> <a href="edit_text.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Sửa văn bản</a></li>
        </ul>';
}

include_once 'footer.php';

