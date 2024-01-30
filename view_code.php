<?php

define('ACCESS', true);

include_once 'function.php';


$themes = ['a11y-light','a11y-dark','vs','xcode','github-dark-dimmed','github'];
$coder = ['Auto','php','javascript','html','json','text'];

function highlightStringWithLineNumbers($code) {
    $code = str_replace("\r\n", "\n", $code);
    $code = str_replace("\r", "\n", $code);
    $lines = explode("\n", $code);
    $lineCount = count($lines);
    $result = [];
    for ($i = 0; $i < $lineCount; $i++) {
        $result[] = sprintf('<span class="line">%3d</span>', $i + 1);
    }
    $text = '';
    for ($i = ($lineCount-1); $i >= 0; $i--) {
        if(isset($lines[$i]) && $lines[$i] != '') break;
        $text .= '<br /> ';
    }
    return array(
        'line' => implode('',$result),
        'text' => $text
    );
}

function detectCodeType($code) {
    if (strpos($code, "<?php") !== false || strpos($code, "<?=") !== false) {
        return "php";
    } elseif (strpos($code, "const ") !== false || strpos($code, "var ") !== false || strpos($code, "function ") !== false || strpos($code, "document.") !== false) {
        return "javascript";
    } elseif (strpos($code, "background-color") !== false || strpos($code, "background") !== false || strpos($code, "-wekit-") !== false) {
        return "css";
    } elseif (strpos($code, "{\"") !== false && strpos($code, "\"}") !== false && strpos($code, "\":\"") !== false){
        return "json";
    } else {
        return "html";
    }
}


$title = 'Xem tập tin';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = $page <= 0 ? 1 : $page;

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

    echo '<link id="classHl" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/vs.min.css">
    <style>
        pre {
            width:80%;
            white-space: wrap;
            overflow-x: auto;
        }
        code {
            line-height: 1.4;
            text-align: left;
            font-size:14px!important;
            padding:0!important;
            width:100%;
        }
        .code {            
            margin-top:0;
        }
        .code div {
            border-bottom:0.5px solid #fff;
        }
        .code, .linecode {     
            margin-top:0;               
            vertical-align: top;
        }
        .codeload::-webkit-scrollbar {
            display:none;
        }
        .linecode, .code, .code span {
            display: inline-block;
        }              
        pre code.hljs, coce.hljs { 
            padding:0px;
            margin-top:0;
        }          
        .line {
            line-height: 1.4;
            font-family: monospace;
            font-size:14px;
            padding-right: 5px;
            display: block;
            text-align: right; 
            color: #999; 
            border-right: 1px solid red;
            background: white;
        }
    </style>';



    echo '<div class="list">
        <span class="bull">&bull; </span><span>' . printPath($dir, true) . '</span><hr/>
        <div class="ellipsis break-word">
            <span class="bull">&bull; </span>Tập tin: <strong class="file_name_edit">' . $name . '</strong><hr/>
        </div>
    </div>';

    echo '<div class="list codeload" style="padding:0;">
        <span class="linecode">
            '. highlightStringWithLineNumbers($content)['line'] .'
        </span>
        <pre class="code">
			<code wrap="off" style="white-space: pre;" class="language-' . detectCodeType($content) .'">'
				. htmlspecialchars($content)
				. highlightStringWithLineNumbers($content)['text']
			. '</code>
		</pre>
    </div>';
    echo '<div class="title">Tùy chỉnh</div>
        <div class="list">
        Giao diện<br />';
    echo '<select id="themes">';
    foreach($themes as $key) {
        echo '<option value="'. $key .'">'.
            $key .'
        </option>';
    }
    echo '</select>
        <hr />
        Cú pháp<br />';
    echo '<select id="coder">';
    foreach($coder as $key) {       
        echo '<option value="'. (($key == 'Auto') ? '' : 'language-'. $key) .'">'.
            $key .'
        </option>';
    }
    echo '</select>
        </div>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
        <!-- and it\'s easy to individually load additional languages -->
        <script>
            hljs.configure({        
	        ignoreUnescapedHTML: true
            });
            hljs.highlightAll();
        </script>';
    echo '<script>
        var codeElements = document.querySelector("code");       
        document.addEventListener("DOMContentLoaded", function() {                        
            var lineElements = document.querySelectorAll(".line");
            var maxWidth = 0;
            var percentWith = 0;
            lineElements.forEach(function(lineElement) {
                var currentWidth = lineElement.offsetWidth;
                if (currentWidth > maxWidth) {
                    maxWidth = currentWidth;
                    percentWidth = document.querySelector(".codeload").offsetWidth - document.querySelector(".linecode").offsetWidth;
                }
            });   
            
            lineElements.forEach(function(lineElement) {
                lineElement.style.width = maxWidth + "px";
            });
            document.querySelector("pre").style.width = (percentWidth-15) + "px";
        });
 
        var elementTheme = document.querySelector("#themes");
        elementTheme.addEventListener("change", function () {
            var currentHref = document.getElementById("classHl").href;
            var newHref = currentHref.replace(/\/[^\/]*$/, "/" + elementTheme.value);
            document.getElementById("classHl").href = newHref + ".min.css";
        });

        var elementCode = document.querySelector("#coder");
        elementCode.addEventListener("change", function () {
            codeElements.className = elementCode.value;
            delete codeElements.dataset.highlighted;
            hljs.highlightAll();
        });                     
    </script>';

    echo '<div class="title">Chức năng</div>
        <ul class="list">
            <li><img src="icon/info.png"/> <a href="file.php?dir='      . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Thông tin</a></li>
        </ul>';
}

include_once 'footer.php';
