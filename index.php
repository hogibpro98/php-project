<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
<?php $notice = !empty($_SESSION['notice']) ? $_SESSION['notice'] : array(); ?>
<?php if (!empty($notice['error'])) : ?>
    <div class="system-modal-error" id="system-modal">
        <div class="system-modal-error-box">
            <div class="system-modal-error-ttl">
                ERROR
            </div>
            <div class="system-modal-error-msg">
                <?php foreach ($notice['error'] as $msg) : ?>
                    <?= $msg . '<br>' ?>
                <?php endforeach; ?>
            </div>
            <div class="system-modal-error-btn">
                <button type="button" id="close">
                    OK
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['notice']); ?>
<?php endif; ?><!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <script>
            $(function () {
                $("#close").click(function () {
                    $("#system-modal").hide();
                });
            });
        </script>
        <!--CONTENT-->
        <title>ログイン - やさしい手</title>
    </head>

    <body class="no_min-w">
        <div id="wrapper">
            <div id="base">
                <!--HEADER-->
                <header id="header">
                    <div class="head">
                        <div class="head-l">
                            <h1><a href="">KANTAKI-WIZ</a></h1>
                        </div>
                    </div>
                </header>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <div id="login">

                        <form action="" class="p-form-validate" method="post">
                            <div class="login_box">
                                <div class="logo"><img src="common/image/logo_login.png" alt="在宅介護やさしい手"></div>
                                <div class="username">			
                                    <label for="username">アカウント名</label>
                                    <input type="text" name="loginId" placeholder="アカウント名を入力してください">
                                </div>
                                <div class="password">			
                                    <label for="password">パスワード</label>
                                    <input type="password" name="loginPass" placeholder="パスワードを入力してください">
                                </div>
                                <!--<div class="login_btn"><a href="/index.php">ログイン</a></div>-->
                                <button type="submit" name="btnLogin" value="true" class="box-login-btn">ログイン</button>
                            </div>
                        </form>
                    </div>
                    <!--/// CONTENT_END ///-->
                </article>
                <!--CONTENT-->
                <!--FOOTER-->
                <footer id="footer">
                    <p class="copy">Copyright 2021  在宅介護やさしい手</p>
                </footer>
                <!--FOOTER-->
            </div>
        </div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>