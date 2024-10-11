<?php require_once(dirname(__FILE__) . "/php/account_edit.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <title>アカウント情報</title>
        <style>
            .input-wrap{
                position: relative;
            }

            .toggle-pass{
                /*position:absolute;*/
                /*top:50%;*/
                /*right: 10px;*/
                /*transform: translateY(-50%);*/
            }
        </style>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <form action="" class="p-form-validate" method="post">
                    <article id="content">
                        <!--/// CONTENT_START ///-->
                        <h2>アカウント情報</h2>
                        <div id="subpage">
                            <div id="staff-detail" class="nursing">
                                <div class="wrap">
                                    <div class="user-details nurse_record">
                                        <ul class="name_info">
                                            <li class="line">
                                                <div>
                                                    <label>アカウント</label>
                                                    <input type="text" readonly name="upAry[account]" class="bg-gray2" value="<?= $dispData['account'] ?>" style="margin-left:30px;width:300px;" autocomplete="false">
                                                </div>
                                            </li>
                                            <li class="line">
                                                <div>
                                                    <label>パスワード</label>
                                                    <input type="password" readonly name="upAry[password]" class="bg-gray2" value="<?= $dispData['password'] ?>" style="margin-left:30px;width:300px;" autocomplete="false">
                                                    <i class="toggle-pass fa fa-eye-slash"></i>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/// CONTENT_END ///-->
                    </article>
                </form >
                <!--CONTENT-->
            </div>
        </div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
        <script>
            $(function () {
                $('.toggle-pass').on('click', function () {
                    $(this).toggleClass('fa-eye fa-eye-slash');
                    var input = $(this).prev('input');
                    if (input.attr('type') == 'text') {
                        input.attr('type', 'password');
                    } else {
                        input.attr('type', 'text');
                    }
                });
            });
        </script>
    </body>
</html>