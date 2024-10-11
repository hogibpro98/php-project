<?php require_once(dirname(__FILE__) . "/php/news_edit.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>お知らせ詳細</title>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <form action="" class="p-form-validate" method="post">
                    <article id="content">
                        <!--/// CONTENT_START ///-->
                        <h2>お知らせ詳細</h2>
                        <div id="subpage"><div id="info" class="nursing">
                                <?php if (isset($dispData['unique_id'])) : ?>
                                    <input type="hidden" name="upAry[unique_id]" value="<?= $dispData['unique_id'] ?>" >
                                <?php endif; ?>
                                <div class="wrap">
                                    <div class="info_form">
                                        <table>
                                            <tr>
                                                <th>拠点</th>
                                                <td>
                                                    <select id="base-name" name="upAry[place_id]">
                                                        <option value="" >▼選択してください</option>
                                                        <option value="" <?= empty($dispData['place_id']) ? ' selected' : '' ?>>全拠点</option>
                                                        <?php foreach ($mstPlace as $idx => $val): ?>
                                                            <option value="<?= $val['unique_id'] ?>" <?= !empty($dispData['place_id']) && ($val['unique_id'] === $dispData['place_id']) ? ' selected' : '' ?>><?= $val['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>タイトル</th>
                                                <td><input type="text" name="upAry[title]" placeholder="タイトルを入力してください" value="<?= $dispData['title'] ?>" required></td>
                                            </tr>
                                            <tr>
                                                <th>内容</th>
                                                <td><textarea name ="upAry[detail]" placeholder="内容を入力してください" required><?= $dispData['detail'] ?></textarea></td>
                                            </tr>
                                        </table>
                                        <div class="info_box">
                                            <dl>
                                                <dt>公開</dt>
                                                <dd>
                                                    <p><input type="radio" name="upAry[status]" id="deploy1" value="1" <?= ($dispData['status'] == '1') ? 'checked' : '' ?>><label for="deploy1">公開</label></p>
                                                    <p><input type="radio" name="upAry[status]" id="deploy2" value="0" <?= ($dispData['status'] == '0') ? 'checked' : '' ?>><label for="deploy2">非公開</label></p>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt>公開日時：</dt>
                                                <dd>
                                                    <p><?= $dispData['create_day'] ?>   <?= $dispData['create_time'] ?></p>
                                                    <p><?= $dispData['create_name'] ?></p>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt>更新日時：</dt>
                                                <dd>
                                                    <p><?= $dispData['update_day'] ?>   <?= $dispData['update_time'] ?></p>
                                                    <p><?= $dispData['update_name'] ?></p>
                                                </dd>
                                            </dl>
                                        </div>
                                        <!-- <div class="control_box">
                                                <p><span class="btn save-op">保存</span></p>
                                        </div> -->
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
                        <div class="fixed_navi uninsure-navi uninsure-d-navi">
                            <div class="box">
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">お知らせ管理にもどる</button></div>
                                <div class="btn back sm"><a href="/place/news_list/"><img src="/common/image/icon_return.png" alt=""></a></div>

                                <div class="controls">
                                    <!--<div class="btn cancel"><a href="/place/news_list">キャンセル</a></div>-->
                                    <button type="submit" name="btnEntry" value="true" class="btn save">保存</button>
                                </div>
                            </div>
                        </div>
                    </article>
                </form >
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>