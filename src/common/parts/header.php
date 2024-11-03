<!-- 禁忌メッセージ -->
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
    <?php unset($_SESSION['notice']['error']); ?>
    <?php unset($_SESSION['notice']['warning']); ?>
<?php // endif;?>
<!-- 警告メッセージ -->
<?php elseif (!empty($notice['warning'])) : ?>
    <div class="system-modal-warning" id="system-modal">
        <div class="system-modal-warning-box">
            <div class="system-modal-warning-ttl">
                warning
            </div>
            <div class="system-modal-warning-msg">
                <?php foreach ($notice['warning'] as $msg) : ?>
                    <?= $msg . '<br>' ?>
                <?php endforeach; ?>
            </div>
            <div class="system-modal-warning-btn">
                <button type="button" id="close">
                    OK
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['notice']['warning']); ?>
<?php endif; ?>
<!-- 通知メッセージ -->
<?php if (!empty($notice['info'])) : ?>
    <div class="system-modal-info" id="system-modal">
        <div class="system-modal-info-box">
            <div class="system-modal-info-ttl">
                info
            </div>
            <div class="system-modal-info-msg">
                <?php foreach ($notice['info'] as $msg) : ?>
                    <?= $msg . '<br>' ?>
                <?php endforeach; ?>
            </div>
            <div class="system-modal-info-btn">
                <button type="button" id="close">
                    OK
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['notice']['info']); ?>
<?php endif; ?>
<!-- 成功メッセージ -->
<?php if (!empty($notice['success'])) : ?>
    <div class="system-modal-success" id="system-modal">
        <div class="system-modal-success-box">
            <div class="system-modal-success-ttl">
                success
            </div>
            <div class="system-modal-success-msg">
                <?php foreach ($notice['success'] as $msg) : ?>
                    <?= $msg . '<br>' ?>
                <?php endforeach; ?>
            </div>
            <div class="system-modal-success-btn">
                <button type="button" id="close">
                    OK
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['notice']['success']); ?>
<?php endif; ?>
<!-- 登録確認 -->
<?php $confirm = !empty($_SESSION['confirm']) ? $_SESSION['confirm'] : array(); ?>
<?php if (!empty($_SESSION['confirm']['entry'])) : ?>
    <form action="" class="p-form-validate" method="post">
        <div class="system-modal-entry is-info" id="system-modal">
            <div class="system-modal-entry-box">
                <div class="system-modal-entry-ttl">
                    ENTRY CONFIRM
                </div>
                <div class="system-modal-entry-msg">
                    <?php foreach ($confirm['entry'] as $msg) : ?>
                        <?= $msg . '<br>' ?>
                    <?php endforeach; ?>
                </div>
                <div class="system-modal-entry-btn">
                    <button type="submit" name="btnEntryFix" value="true"><i class="fas fa-check mr5"></i>Yes</button>
                    <button type="submit" name="btnEntryNo" value="true" id="close" class="bg-gray"><i class="fas fa-times mr5"></i>No</button>
                </div>
            </div>
        </div>
    </form>
    <?php unset($_SESSION['confirm']['entry']); ?>
<?php endif; ?>
<!-- 削除確認 -->
<?php if (!empty($_SESSION['confirm']['delete'])) : ?>
    <form action="" class="p-form-validate" method="post">
        <div class="system-modal-delete is-info" id="system-modal">
            <div class="system-modal-delete-box">
                <div class="system-modal-delete-ttl">
                    DELETE CONFIRM
                </div>
                <div class="system-modal-delete-msg">
                    <?php foreach ($confirm['delete'] as $msg) : ?>
                        <?= $msg . '<br>' ?>
                    <?php endforeach; ?>
                </div>
                <div class="system-modal-delete-btn">
                    <button type="submit" name="btnDelFix" value="true"><i class="fas fa-check mr5"></i>Yes</button>
                    <button type="submit" name="btnDelNo" value="true" id="close" class="bg-gray"><i class="fas fa-times mr5"></i>No</button>
                </div>
            </div>
        </div>
    </form>
    <?php unset($_SESSION['confirm']['delete']); ?>
<?php endif; ?>
<script>
    $(function() {
        $("#close").click(function() {
            $("#system-modal").hide();
        });
    });
</script>
<?php
// 拠点情報の取得
$placeList = isset($loginUser['place']) ? $loginUser['place'] : array();
$placeId = filter_input(INPUT_GET, 'place');
if ($placeId) {
    $_SESSION['place'] = $placeId;
} else {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
}
$placeMst = getPlaceInfo($placeId);
?>
<!-- ローディング -->
<!--<div class="loading">
    <img src="/common/image/icn_loading.gif" alt="LOADING">
</div>-->
<script>
    $(function(){

    // セレクト選択でサブミット(GET)
    $(".nav-search select").on("change",function(){
        $(".main").remove();
        $(this).after('<input type="hidden" name="btnSearch" value="true">');
        $(this).parents("form").find("input,select").prop("disabled",false);
        $(this).parents("form").submit();
    });
});
    </script>
<!--HEADER-->
<header id="header">
    <div class="head">
        <div class="head-l">
            <h1><a href="<?= TOP_PAGE ?>">KANTAKI-WIZ</a></h1>
            <div class="sm acct_icon">
                <span class="display_switch"><img src="/common/image/icon_setting.svg" alt=""><small>拠点</small></span>
            </div>
        </div>
        <div class="head-r">
            <form metod="GET" class="p-form-validate" action="">
                <div class="nav-search">
                    <select name="place">
                        <option value="">選択してください</option>
                        <?php foreach ($placeList as $tgtId => $val): ?>
                        <?php $select = $placeId === $tgtId ? ' selected' : null; ?>
                        <option value="<?= $tgtId ?>"<?= $select ?>><?= $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
            <div class="box3">
                <div class="num2"><?= $loginUser['staff_id'] ?></div>
                <div class="acct_log2"><?= $loginUser['name'] ?></div>
            </div>

            <form method="POST">
            <div class="box4">
                    <button class="logout-btn" name="btnLogout" value="true">ログアウト</button>
                </div>
            </form>
        </div>
    </div>

    <!--MENU-->
    <input type="checkbox" class="openNav closeNav" id="openNav">
    <label for="openNav" class="navIconToggle">
        <span class="menu_btn">メニュ|</span>
        <span class="spinner diagonal part-1"></span>
        <span class="spinner horizontal"></span>
        <span class="spinner diagonal part-2"></span>
    </label>

    <div id="Nav">
        <ul>
            <li id="nav1">
                <div class="list1">利用者</div>
                <ul class="nav-nest1">
                    <li>
                        <div><a href="/user/list/index.php">利用者一覧</a></div>
                    </li>
                    <li>
                        <div><a href="/user/edit/index.php">利用者基本情報</a></div>
                    </li>
                    <li>
                        <div><a href="/record/user/index.php">利用者予定実績</a></div>
                    </li>
                    <li>
                        <div><a href="/schedule/week/index.php">週間スケジュール</a></div>
                    </li>
                    <li>
                        <div><a href="/image/list/index.php">画像関連一覧</a></div>
                    </li>
                    <li>
                        <div class="list2">各種帳票</div>
                        <ul class="nav-nest2">
                            <li>
                                <div><a href="/report/list/">各種帳票</a></div>
                            </li>
                            <!-- <li>
                                <div><a href="/report/print_list/index.php">各種帳票</a></div>
                            </li> -->
                            <li>
                                <div><a href="/report/bedsore/index.php">褥瘡計画</a></div>
                            </li>
                            <li>
                                <div><a href="/report/instruct/index.php">指示書</a></div>
                            </li>
                            <li>
                                <div><a href="/report/plan/index.php">計画書</a></div>
                            </li>
                            <li>
                                <div><a href="/report/report/index.php">報告書</a></div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="list2">記録一覧</div>
                        <ul class="nav-nest2">
                            <li>
                                <div><a href="/report/report_list/index.php">記録一覧</a></div>
                            </li>
                            <li>
                                <div><a href="/report/progress/index.php">経過記録</a></div>
                            </li>
                            <li>
                                <div><a href="/report/kantaki/index.php">看多機記録</a></div>
                            </li>
                            <li>
                                <div><a href="/report/visit1/index.php">訪問看護記録Ⅰ</a></div>
                            </li>
                            <li>
                                <div><a href="/report/visit2/index.php">訪問看護記録Ⅱ</a></div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li id="nav2">
                <div class="list1">従業員</div>
                <ul class="nav-nest1">
                    <li>
                        <div><a href="/record/staff/index.php">従業員予定実績</a></div>
                    </li>
                    <li>
                        <div><a href="/schedule/route_edit/index.php">ルート管理</a></div>
                    </li>
                    <li>
                        <div><a href="/schedule/route_day/index.php">ルート表</a></div>
                    </li>
                    <li>
                        <div><a href="/place/news/index.php">お知らせ表示</a></div>
                    </li>
                </ul>
            </li>
            <li id="nav3">
                <div class="list1">拠点</div>
                <ul class="nav-nest1">
                    <li>
                        <div><a href="/report/all_list/index.php">帳票一括確認</a></div>
                    </li>
                    <li>
                        <div><a href="/place/csv/index.php">CSVデータ出力</a></div>
                    </li>
                    <li>
                        <div><a href="/place/cooperate/index.php">連携データ作成</a></div>
                    </li>
                    <li>
                        <div><a href="/place/news_list/index.php">お知らせ管理</a></div>
                    </li>
                </ul>
            </li>
            <li id="nav4">
                <div class="list1">システム</div>
                <ul class="nav-nest1">
                    <li>
                        <div><a href="/system/staff_list/index.php">従業員一覧</a></div>
                    </li>
                    <li>
                        <div><a href="/system/place_list/index.php">拠点管理</a></div>
                    </li>
                    <li>
                        <div><a href="/system/office/index.php">事業所管理</a></div>
                    </li>
                    <li>
                        <div><a href="/system/uninsure_list/index.php">保険外マスタ</a></div>
                    </li>
                    <li>
                        <div><a href="/system/log/index.php">ログ管理</a></div>
                    </li>
                    <li>
                        <div><a href="/system/account/index.php">アカウント情報</a></div>
                    </li>
                    <?php if (HTJ_FLG && $loginUser): ?>
                    <?php $htjMenu = getHtjMenu($loginUser); ?>
                    <?php if ($htjMenu): ?>
                    <?php $htjCnt = $htjCnt ? '（' . getHtjUnread($loginUser) . '）' : null; ?>
                    <li>
                        <div><a href="<?= $htjMenu ?>">ひつじ連携<?= $htjCnt ?></a></div>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </div>
</header>
<!--HEADER-->