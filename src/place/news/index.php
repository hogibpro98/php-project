<?php require_once(dirname(__FILE__) . "/php/news.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <link rel="stylesheet" href="/common/css/top.css" media="all">
        <title>やさしい手</title>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <div id="toppage">
                        <h2 class="notice_tit">事業所からのお知らせ</h2>
                        <div class="latest_notice notice">
                            <div class="notice_list">
                            </div>
                        </div>
                        <div class="box1 info">
                            <form action="" class="p-form-validate" method="get">
                                <div class="date_arch">
                                    <select name="sAry[release_date]">
                                        <option value=""></option>
                                        <?php foreach ($dispNewsMonth as $key => $val): ?>
                                            <option value="<?= $key ?>" <?= $dispSearch['release_date'] == $key ? 'selected' : '' ?>><?= $key ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="search_box">
                                    <button type="image"><img src="/common/image/icon_search.png" alt="Submit"></button>
                                    <input type="text" name="sAry[freeword]" value="<?= $dispSearch['freeword'] ?>" placeholder="お知らせを検索">
                                </div>
                                <span style="margin-left:15px;"><input type="checkbox" name="sAry[readed]" id="readed" value="1" <?= ($dispSearch['readed'] != '') ? 'checked' : '' ?>><label for="readed">既読も表示</label></span>
                                <button type="submit" name="btnSearch" value="true" class="btn search" style="margin-left:30px;">絞り込み</button>
                            </form>
                        </div>
                        <div class="latest_notice notice">
                            <div class="notice_list">
                                <?php if(!empty($dispData)) : ?> 
                                <?php foreach ($dispData as $newsMonth => $newsList): ?>
                                    <?php foreach ($newsList as $newsId => $val): ?>
                                        <dl>
                                            <dt>
                                                <div class="day"><?= $val['create_day'] ?></div>
                                                <div class="tit"><?= $val['title'] ?></div>
                                            </dt>
                                            <dd <?= ($val['read_flg'] == '1') ? 'style="display: block;"' : '' ?>>
                                                <div class="cont_box">
                                                    <?= nl2br($val['detail']) ?>
                                                </div>
                                                <div class="date_pub">
                                                    <div class="day_pub"><span>公開日時：</span><span><?= $val['create_day'] ?>   <?= $val['create_time'] ?></span></div>
                                                    <div class="day_edit"><span>更新日時：</span><span><?= $val['update_day'] ?>   <?= $val['update_time'] ?></span></div>
                                                </div>
                                            </dd>
                                        </dl>
                                        <div class="btn_box" <?= ($val['read_flg'] == '1') ? 'style="display: none;"' : '' ?>>
                                            <form action="<?= $server['requestUri'] ?>" class="p-form-validate" method="post">
                                                <input type="hidden" name="upAry[unique_id]" value="<?= $val['news_status_id'] ?>">
                                                <input type="hidden" name="upAry[news_id]" value="<?= $val['unique_id'] ?>">
                                                <button type="submit" name="btnRead" value="true" class="btn-read" style="padding:5px;height:38px;font-size:14px;">既読に変更</button>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                <?php else : ?>
                                    <div>表示するお知らせはありません。</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- ページャー -->
                        <?php dispPager($tgtData, $page, $line, $server['requestUri']) ?>
                    </div>
                    <!--/// CONTENT_END ///-->
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>