<?php require_once(dirname(__FILE__) . "/php/news_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>お知らせ管理</title>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <h2>お知らせ管理</h2>
                    <div id="subpage"><div id="info" class="nursing">

                            <div class="wrap">
                                <div class="info_wrap">
                                    <form action="" class="p-form-validate" method="get">
                                        <div class="info_head" style="display:flex;">
                                            <!--                                            <div class="btn display">全拠点表示</div>-->
                                            <p><input type="radio" name="srchAry[target]" id="deploy1" value="1" <?= ($dispSearch['target'] == '1') ? 'checked' : '' ?>><label for="deploy1">全て</label></p>
                                            <p style="margin-left:20px;"><input type="radio" name="srchAry[target]" id="deploy2" value="2" <?= ($dispSearch['target'] == '2') ? 'checked' : '' ?>><label for="deploy2">自拠点＋共通</label></p>
                                            <p style="margin-left:20px;"><input type="radio" name="srchAry[target]" id="deploy3" value="3" <?= ($dispSearch['target'] == '3') ? 'checked' : '' ?>><label for="deploy3">自拠点のみ</label></p>

                                            <div class="date_arch" style="margin-left:20px;">
                                                <input type="month" name="srchAry[release_date]" value="<?= $dispSearch['release_date'] ?>" style="width:150px;">
                                            </div>
                                            <div class="search_box">
                                                <button type="submit" name="btnSearch" value="true" ><img src="/common/image/icon_search.png" alt="Submit"></button>
                                                <input type="text" name="srchAry[detail]" value="<?= $dispSearch['detail'] ?>" placeholder="お知らせを検索">
                                            </div>
                                            <div style="margin-left:20px;">
                                                <button type="submit" name="btnSearch" value="true" class="btn search">絞り込み</button>
                                            </div>
                                            <!--<button type="button" class="btn add" style="height:36.6px;margin-top:10px;"><a href="/place/news_edit">新規登録</a></button>-->
                                            <div class="btn add" style="height:36.6px;width:126.7px;padding:0px;">
                                                <a href="/place/news_edit" style="margin-left:20px;font-size:14px;font-family:"Noto Sans JP", "Yu Gothic", YuGothic, "Hiragino Kaku Gothic Pro", "Meiryo", sans-serif;">新規登録</a>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="info_list">
                                        <table class="main_list">
                                            <tr class="list_tit">
                                                <th class="base">拠点</th>
                                                <th class="publish_d">公開日時</th>
                                                <th class="user">ユーザー</th>
                                                <th class="update_d">更新日時</th>
                                                <th class="user">ユーザー</th>
                                                <th class="title">タイトル</th>
                                                <th class="deploy">公開</th>
                                                <th class="control"></th>
                                            </tr>
                                            <?php foreach ($dispData as $tgtId => $val): ?>
                                                <tr>
                                                <form action="" class="p-form-validate" method="get">
                                                    <input type="hidden" name="id" value="<?= $val['unique_id'] ?>">
                                                    <td style="min-width:150px;"><?= $val['place_name'] ?></td>
                                                    <td><?= $val['create_day'] ?><small><?= $val['create_time'] ?></small></td>
                                                    <td style="min-width:150px;"><?= $val['create_name'] ?></td>
                                                    <td><?= $val['update_day'] ?><small><?= $val['update_time'] ?></small></td>
                                                    <td style="min-width:150px;"><?= $val['update_name'] ?></td>
                                                    <td><?= $val['title'] ?></td>
                                                    <td><?= $val['status'] ?></td>
                                                    <td>
                                                        <p class="btn_box">
                                                            <span class="btn edit"><a href="/place/news_edit?id=<?= $val['unique_id'] ?>" style="padding: 0px;font-size:15px;margin-top:5px;text-align: center; height:19px;margin-top:">編集</a></span>
                                                            <span class="delete"><button type="submit" name="btnDelete" value="true" class="btn delete" style="margin-left:10px; font-size:14px;text-align: center; height:34px;">削除</button></span>
                                                        </p>
                                                    </td>
                                                </form>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                    <!-- ページャー -->
                                    <?php dispPager($tgtData, $page, $line, $server['requestUri']) ?>
                                </div>
                            </div>
                            <div class="msg_box msg_saved cancel_act">
                                <div class="close close_part">✕<span>閉じる</span></div>
                                <div class="msg_box-cont">保存しました</div>	
                            </div> 

                            <div class="msg_box msg_cancelled cancel_act">
                                <div class="msg_box-tit">ご確認ください</div>
                                <div class="msg_box-cont">変更内容は保存されません。<br/>よろしいですか？？</div>
                                <div class="msg_box-btn">
                                    <span class="msg_box-cancel cancel">キャンセル</span>
                                    <span class="msg_box-dlt">OK</span>
                                </div>
                            </div> 
                        </div></div>
                    <!--/// CONTENT_END ///-->
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>