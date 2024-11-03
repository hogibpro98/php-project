<?php require_once(dirname(__FILE__)."/php/progress.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/parts/common.php'); ?>
<!--CONTENT-->
<title>経過記録</title>
<?php foreach ($otherWindowURL as $otherURL):?>
<script>
    $(function(){
        window.open('<?= $otherURL ?>','_blank');
    });
</script>
<?php endforeach; ?>
</head>

<body>
<div id="wrapper"><div id="base">
<!--HEADER-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/parts/header.php'); ?>
<!--CONTENT-->
<article id="content">
<!--/// CONTENT_START ///-->
<form action="" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
<h2 class="tit_sm">経過記録</h2>
<div id="patient" class="sm">大橋 花子</div>
<div id="subpage"><div id="record-detail" class="nursing">

<div class="wrap">
                                        <ul class="user-tab">
                                        <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>

    <div class="nurse_record user-details">
        <div class="d_left">
            <div class="box1 profile">
                <div class="user_id">
                    <span class="label_t">利用者ID<span class="req">*</span></span>
                    <p class="n_search user_search">Search</p>
                    <input type="text" name="upDummy[other_id]" class="n_num tgt-usr_id" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                    <input type="hidden" name="upAry[user_id]" class="n_num tgt-unique_id" value="<?= $userId ?>">
                </div>
                <div class="name">
                    <span class="label_t">利用者氏名<span class="req">*</span></span>
                    <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="n_name tgt-usr_name bg-gray2" readonly>
                </div>
                <!--<div class="entry">-->
                <div class="entry">
                    <span class="label_t">記入者<span class="req">*</span></span>
                    <p class="n_search staff_search">Search</p>
                    <input type="hidden" class="n_num tgt-stf_id" name="upAry[staff_id]" value="<?= $dispData['staff_id'] ?>">
                    <input type="text" class="n_num tgt-stf_cd" name="upDummy[staff_cd]" value="<?= $dispData['staff_cd'] ?>">
                    <input type="text" class="n_name tgt-stf_name bg-gray2" name="upDummy[staff_name]" value="<?= $dispData['staff_name'] ?>" readonly="">
                    <?php $checked = !empty($dispData['importantly']) ? ' checked' : NULL; ?>
                    <span class="juuyou"><input type="checkbox" name="upAry[importantly]" value="重要" id="importance1" <?= $checked ?>><label for="importance1">重要</label></span>
                </div>
            </div>
            <div class="box2">
                <div class="entry_date">
                    <span class="label_t">記入日<span class="req">*</span></span>
                    <input type="date" name="upAry[record_day]" class="" value="<?= $dispData['record_day'] ?>">
                </div>
                <div class="hassei">
                    <span class="label_t">発生日時<span class="req">*</span></span>
                    <input type="date" name="upDummy[target_date]" class="" value="<?= $dispData['target_date'] ?>">
                    <input type="time" name="upDummy[target_time]" class="" value="<?= $dispData['target_time'] ?>">
                </div>
                <div class="kubun sm">
                    <span class="juuyou">
                        <?php $checked = !empty($dispData['importantly']) ? ' checked' : NULL; ?>
                        <input type="checkbox" name="upAry[importantly]" value="重要" <?= $checked ?>>
                        <label>重要</label>
                    </span>
                </div>
                <div class="cont_div div1">					
                    <span class="label_t">内容区分①</span>
                    <select name="upAry[type1]">
                        <option value=""></option>
                        <?php foreach ($gnrList['内容区分①'] as $key => $val): ?>
                            <?php $select = $dispData['type1'] == $val ? ' selected' : NULL; ?>
                            <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="cont_div div2">					
                    <span class="label_t">内容区分②</span>
                    <select name="upAry[type2]">
                        <option value=""></option>
                        <?php foreach ($gnrList['内容区分②'] as $key => $val): ?>
                            <?php $select = $dispData['type2'] == $val ? ' selected' : NULL; ?>
                            <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="d_right">
            <p>
                <button type="submit" class="btn print" name="btnPrint" value="<?= $dispData['unique_id'] ?>">印刷</button>
                <button type="submit" class="btn-del" name="btnDel" value="<?= $dispData['unique_id'] ?>">削除</button>
            </p>
        </div>
    </div>
    <div class="msg_cont">
        <table>
            <tr>
                <th><span class="label_t">件名</span><span class="req">*</span></th>
                <td>
                    <textarea name="upAry[title]" value="<?= $dispData['title'] ?>" class="validate[maxSize[200]]" maxlength="25"><?= $dispData['title'] ?></textarea>
                </td>
            </tr>
            <tr>
                <th><span class="label_t">状況・課題</span><span class="req">*</span></th>
                <td>
                    <textarea name="upAry[problem]" value="<?= $dispData['problem'] ?>" class="" maxlength="800"><?= $dispData['problem'] ?></textarea>
                </td>
            </tr>
            <tr>
                <th><span class="label_t">指示事項</span></th>
                <td>
                    <textarea name="upAry[direction]" value="<?= $dispData['direction'] ?>" class="" maxlength="150"><?= $dispData['direction'] ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <div class="nurse_record record8">
        <span class="label_t">作成状態</span>
        <?php foreach ($gnrList['作成状態'] as $key => $val): ?>
            <?php $check = $dispData['status'] === $val ? ' checked' : NULL; ?>
            <input type="radio" name="upAry[status]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
        <?php endforeach; ?>
    </div>
    <div class="nurse_record record9">
            <div class="i_register">
                <span class="label_t">初回登録:</span>
                <span class=" hidzuke"><?= $dispData['create_day'] ?></span>
                <span class=" time"><?= $dispData['create_time'] ?></span>
                <span class=""><?= $dispData['create_name'] ?></span>
            </div>
            <div class="l_update">
                <span class="label_t">最終更新:</span>
                <span class=" hidzuke"><?= $dispData['update_day'] ?></span>
                <span class=" time"><?= $dispData['update_time'] ?></span>
                <span class=""><?= $dispData['update_name'] ?></span>
            </div>
    </div>
    <!--ダイアログ呼出し-->
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/dialog/user.php'); ?>
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/dialog/staff.php'); ?>
</div>

<div class="msg_box send_msg">
    <div class="msg_box-tit">メール送信</div>
    <div class="msg_box-cont">経過記録メールを送信しますか？</div>
    <div class="msg_box-btn">
        <span class="msg_box-cancel">キャンセル</span>
        <span class="msg_box-send">送信</span>
        <span class="msg_box-close">閉じる</span>
    </div>
</div>

</div></div>
<!--/// CONTENT_END ///-->
<div class="fixed_navi patient_navi record-navi">
    <div class="box">
        <!--<div class="btn back pc"><button type="submit" name="btnReturn" value="true">利用者一覧にもどる</button></div>-->
        <div class="btn back pc"><button type="submit" name="btnReturn" value="true">経過記録一覧にもどる</button></div>
        <div class="btn back sm"><a href="/report/report_list/index.php"><img src="/common/image/icon_return.png" alt="Return"></a></div>
        <div class="controls">
            <button type="submit" class="btn save" name="btnEntry" value="保存">保存
        </div>
    </div>
</div>
<script>


</script>
</form>
</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>
</html>