<?php

define('ACCESS', true);

include_once 'function.php';

if (!IS_LOGIN) {
    goURL('login.php');
}

$title = 'Cập nhật';

include_once 'header.php';

echo '<div class="title">' . $title . '</div>';

$remoteVersion = getNewVersion();

if ($remoteVersion === false) {
    echo '<div class="list">Lỗi máy chủ cập nhật!</div>';
} else {
    if (isset($_POST['submit'])) {
        if (
            !isset($_POST['token'])
            || !isset($_SESSION['token'])
            || $_POST['token'] != $_SESSION['token']
        ) {
            unset($_SESSION['token']);
            goURL('update.php');
        }

        $file = 'manager-' . time() . '.zip';

        if (import($remoteFile, $file)) {

            include 'pclzip.class.php';

            $zip = new PclZip($file);

            if (
                $zip->extract(
                    PCLZIP_OPT_PATH,
                    dirname(__FILE__),
                    PCLZIP_OPT_REPLACE_NEWER
                ) != false
            ) {
                @unlink($file);

                goURL('update.php');
            } else {
                echo '<div class="list">Lỗi! Không thể cài đặt bản cập nhật</div>';
            }

            /*
            $zip = new ZipArchive;
            if ($zip->open($file) === true) {
                $zip->extractTo(dirname(__FILE__));
                $zip->close();
                @unlink($file);

                echo '<div class="list">Cập nhật thành công</div>';
            } else {
                echo '<div class="list">Lỗi</div>';
            }
            */
        } else {
            echo '<div class="list">Lỗi! Không thể tải bản  cập nhật</div>';
        }
    } else {
        $token = time();
        $_SESSION['token'] = $token;

        if (
            intval($remoteVersion['major']) == VERSION_MAJOR
            && intval($remoteVersion['minor']) == VERSION_MINOR
            && intval($remoteVersion['patch']) == VERSION_PATCH
        ) {
            echo '<div class="list">
                    Bạn đang sử dụng phiên bản manager mới nhất!<br />
                    Ấn "Cập nhật" để cài đặt lại phiên bản hiện tại!
                </div>';
        }

        echo '<div class="list">
            <span>Có phiên bản mới <b>' . $remoteVersion['major'] . '.' . $remoteVersion['minor'] . '.' . $remoteVersion['patch'] . '</b>, bạn có muốn cập nhật?</span><hr />
            <span>' . $remoteVersion['message'] . '</span><hr />
            <form action="update.php" method="post">
                <input type="hidden" name="token" value="' . $token . '" />
                <input type="submit" name="submit" value="Cập nhật"/>
            </form>
            </div>';
    }
}

include_once 'footer.php';
