<?php require_once(dirname(__FILE__)."/php/print_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/parts/common.php'); ?>
<!--CONTENT-->
<title>各種帳票</title>
</head>

<body>
<div id="wrapper"><div id="base">
<!--HEADER-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/parts/header.php'); ?>
<!--CONTENT-->
<article id="content">
<form action="" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
<!--/// CONTENT_START ///-->
<h2>各種帳票</h2>
<div id="subpage"><div id="register" class="nursing">

    <div class="wrap">

        <ul class="tab">
            <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
            <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
            <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
        </ul>

        <div class="user-details pc">
            <div class="d_left">
                <div class="line profile">
                    <dl>
                        <dt>利用者ID</dt>
                        <dd>
                            <p class="n_search user_search">Search</p>
                            <input type="text" name="search[other_id]" class="n_num tgt-usr_id" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                            <input type="hidden" name="search[user_id]" class="n_num tgt-unique_id" value="<?= $keyId ?>">
                        </dd>
                    </dl>
                    <dl>
                        <dt>利用者氏名</dt>
                        <dd>
                            <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="n_name tgt-usr_name bg-gray2" readonly>
                        </dd>
                    </dl>
                    <dl>
                        <dd>
                            <button type="submit" name="btnSearch" value="true" class="btn search">検索</button>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-l">
                <div class="table-box table1">
                    <div class="tit_box">
                        <p class="tit">訪問看護記録Ⅰ</p>
                        <p class="btn_box"><span class="btn add"><a href="/report/visit1/index.php?user=<?= $keyId ?>">新規作成</a></span></p>
                    </div>
                    <div class="t_wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>作成日</th>
                                    <th>担当者</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php foreach ($dispVst as $key => $val): ?>
                                <?php $i++; ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $val['report_day'] ?></td>
                                    <td><?= $val['staff_name'] ?></td>
                                    <td><p class="btn_box"><span class="btn edit"><a href="/report/visit1/index.php?id=<?= $key ?>">編集</a></span><span class="btn duplicate"><a href="/report/visit1/index.php?copy=<?= $key ?>">複製</a></span></p></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-box table2">
                    <div class="tit_box">
                        <p class="tit">褥瘡計画書</p>
                        <p class="btn_box"><span class="btn add"><a href="/report/bedsore/index.php?user=<?= $keyId ?>">新規作成</a></span></p>
                    </div>
                    <div class="t_wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>計画作成日</th>
                                    <th>担当者</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php foreach ($dispBed as $key => $val): ?>
                                <?php $i++; ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $val['plan_day'] ?></td>
                                    <td><?= $val['staff_name'] ?></td>
                                    <td><p class="btn_box"><span class="btn edit"><a href="/report/bedsore/index.php?id=<?= $key ?>">編集</a></span><span class="btn duplicate"><a href="/report/bedsore/index.php?copy=<?= $key ?>">複製</a></span></p></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-box table3">
                    <div class="tit_box">
                        <p class="tit">指示書</p>
                        <p class="btn_box"><span class="btn add"><a href="/report/instruct/index.php?user=<?= $keyId ?>">新規作成</a></span></p>
                    </div>
                    <div class="t_wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>訪問看護区分</th>
                                    <th>指示区分</th>
                                    <th>指示開始</th>
                                    <th>指示終了</th>
                                    <th>PDF</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php foreach ($dispIns as $key => $val): ?>
                                <?php $i++; ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $val['care_kb'] ?></td>
                                    <td><?= $val['direction_kb'] ?></td>
                                    <td><?= $val['direction_start'] ?></td>
                                    <td><?= $val['direction_end'] ?></td>
                                    <td><?= !empty($val['pdf_file']) ? "〇" : '' ?></td>
                                    <td><p class="btn_box"><span class="btn edit"><a href="/report/instruct/index.php?id=<?= $key ?>">編集</a></span><span class="btn duplicate"><a href="/report/instruct/index.php?copy=<?= $key ?>">複製</a></span></p></td>
                                </tr>
                                <?php endforeach                                                                                                                                 ; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="table-box table4">
                <div class="tit_box">
                    <p class="tit">計画書・報告書</p>
                    <p class="btn_box"><span class="btn add"><a href="/report/plan/index.php?user=<?= $keyId ?>">計画書を新規作成</a></span><span class="btn add"><a href="/report/report/index.php?user=<?= $keyId ?>">報告書を新規作成</a></span></p>
                </div>
                <div class="t_wrap">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>様式</th>
                                <th>訪問看護区分</th>
                                <th>該当月</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            <?php if (count($dispData2) > 0): ?>
                            <?php foreach ($dispData2 as $key => $val): ?>
                            <?php $i++; ?>
                            <tr class="navigate">
                                <td></td>
                                <td><?= $val['type'] ?></td>
                                <td><?= $val['care_kb'] ?></td>
                                <td><?= $val['month'] ?></td>
                                <td>
                                    <?php $type = $val['type'] == '計画書' ? 'plan' : 'report'; ?>
                                    <p class="btn_box">
                                        <span class="btn edit">
                                            <a href="/report/<?= $type ?>/index.php?id=<?= $key ?>">編集</a>
                                        </span>
                                        <span class="btn duplicate">
                                            <a href="/report/<?= $type ?>/index.php?copy=<?= $key ?>">複製</a>
                                        </span>
                                    </p>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>			
                </div>
            </div>
        </div>
        <!--ダイアログ呼出し-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/dialog/user.php'); ?>
    </div>

</div></div>
<!--/// CONTENT_END ///-->
</form>
</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>
</html>