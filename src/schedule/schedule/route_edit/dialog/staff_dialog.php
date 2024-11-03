<?php
/* ===================================================
 * スタッフ編集モーダル
 * ===================================================
 */

/* ===================================================
 * 初期処理
 * ===================================================
 */

/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/
// 初期化
$err      = array();
$_SESSION['notice']['error'] = array();
$dispData = array();
$tgtData  = array();
$userIds  = array();
$userList = array();
$tgtData  = array();
$upAry    = array();

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// 拠点ID
$placeId = filter_input(INPUT_GET, 'place');
if (!$placeId) {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
}

// スケジュールID
$schId = filter_input(INPUT_GET, 'scheduleId');
if (!$schId) {
    $schId = !empty($_SESSION['scheduleId']) ? $_SESSION['scheduleId'] : null;
}

/*-- 更新用パラメータ ---------------------------------------*/

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */


/* -- データ取得 --------------------------------------------*/

/* -- スタッフ予定 ---------------------------------*/
$where = array();
$where['delete_flg'] = 0;
$where['unique_id']  = $schId;
$temp = select('dat_staff_schedule', '*', $where);

foreach ($temp as $val) {

    // 曜日、開始・終了時刻、更新者名
    $val['week_name']   = $weekAry[$val['week']];
    $val['start_time']  = formatDateTime($val['start_time'], 'H:i');
    $val['end_time']    = formatDateTime($val['end_time'], 'H:i');
    $val['update_name'] = getStaffName($val['update_user']);

    // 格納
    $tgtData = $val;
}


/* -- 画面表示データ格納 ----------------------------*/
$dispData = $tgtData;
?>

<div class="modal new_default sched_default displayed_part cancel_act">
    <div class="modal_close close close_part">✕<span class="modal_close">閉じる</span></div>
    <div class="sched_tit">利用者スケジュール登録</div>
    <div class="s_detail">
        <div class="box1">
            <p class="mid">曜日/時刻</p>
            <p>
                <select class="s_month">
                    <option selected>月</option>
                    <option>火</option>
                    <option>水</option>
                    <option>木</option>
                    <option>金</option>
                    <option>土</option>
                    <option>日</option>
                </select>
                <input type="text" name="time_from" placeholder="時間" value="10:00"><small>～</small><input type="text" name="time_to" placeholder="時間" value="10:30">
            </p>
            <p class="month_list">
                <?php   ?>
                <span><input type="checkbox" name="upAry[week1]" value="<?= $upAry['week1'] ?>" id="week1" value="1" <?= $dispData['retired'] == '1' ? 'checked' : '' ?>checked><label for="month1">第1週</label></span>
                <span><input type="checkbox" name="upAry[week2]" value="<?= $upAry['week2'] ?>" id="week2"><label for="month2">第2週</label></span>
                <span><input type="checkbox" name="upAry[week3]" value="<?= $upAry['week3'] ?>" id="week3" checked><label for="month3">第3週</label></span>
                <span><input type="checkbox" name="upAry[week4]" value="<?= $upAry['week4'] ?>" id="week4"><label for="month4">第4週</label></span>
                <span><input type="checkbox" name="upAry[week5]" value="<?= $upAry['week5'] ?>" id="week5" checked><label for="month5">第5週</label></span>
                <span><input type="checkbox" name="upAry[week6]" value="<?= $upAry['week6'] ?>" id="week6" checked><label for="month5">第6週</label></span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">利用者</p>
            <p>
                <span class="user_res"><?= $dispData['scheduleData']['base_schedule']["'" . $scheduleId . "'"]['last_name'] . " " . $dispData['scheduleData']['base_schedule']["'" . $scheduleId . "'"]['first_name'] ?></span>
                <span class="user_res"><?= $dispData['scheduleData']['wscd00000001']['last_name'] . " " . $dispData['scheduleData']['base_schedule']['wscd00000001']['first_name'] ?></span>
                <span class="label_t">(利用者ID: <?= $dispData['scheduleData']['base_schedule']["'" . $scheduleId . "'"]['other_id'] ?>)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">実施事業所</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff">本社</span>
                <span class="staff_id">(ID:0001332)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">ルート</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff select-gray">選択してください</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">サービス内容</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff select-gray">選択してください</span>
            </p>
            <p class="own_expense">
                <span><label>自費</label><input type="checkbox" name="own_expense" id="expense"></span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">基本サービス<br class="pc">コード</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff select-gray">選択してください</span>
            </p>
        </div>
    </div>
    <div class="add_sub">
        <p class="mid">加減算</p>
        <ol>
            <li>
                <select class="default">
                    <option disabled hidden selected>選択してください</option>
                </select>
            </li>
        </ol>
        <p class="add_btn add_sub_btn">+</p>
    </div>
    <div class="cost">
        <p class="mid">実費</p>
        <table>
            <tr>
                <th class="type">種類</th>
                <th class="item">項目名称</th>
                <th class="price">単価<br>最大7桁</th>
                <th class="tax">消費税<br>区分</th>
                <th class="sales_tax">消費税率</th>
                <th class="d_cate">控除区分</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td class="type">
                    <b class="sm">種類</b>
                    <select>
                        <option selected>食事朝</option>
                        <option>食事夕</option>
                        <option>自費</option>
                    </select>
                </td>
                <td class="item">
                    <b class="sm">項目名称</b>
                    <select>
                        <option selected>朝食代(刻み食・ミキサー食)</option>
                        <option>夕食</option>
                        <option>訪看サービス自費(交通費含む・1時間未満)</option>
                    </select>
                </td>
                <td class="price"><b class="sm">単価最大7桁</b><input type="text" name="単価" value="500"></td>
                <td class="tax">
                    <b class="sm">消費税<br>区分</b>
                    <select>
                        <option selected>税込</option>
                        <option>税込</option>
                        <option>税込</option>
                    </select>
                </td>
                <td class="sales_tax"><b class="sm">消費税率</b><input type="text" name="単価" value="0"><span>%</span></td>
                <td class="d_cate">
                    <b class="sm">控除区分</b>
                    <select>
                        <option selected>控除対象外</option>
                        <option>控除対象外</option>
                        <option>控除対象外</option>
                    </select>
                </td>
                <td>
                    <p class="list_delete">Delete</p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="type">
                    <b class="sm">種類</b>
                    <select>
                        <option>食事朝</option>
                        <option selected>食事夕</option>
                        <option>自費</option>
                    </select>
                </td>
                <td class="item">
                    <b class="sm">項目名称</b>
                    <select>
                        <option>朝食代(刻み食・ミキサー食)</option>
                        <option selected>夕食</option>
                        <option>訪看サービス自費(交通費含む・1時間未満)</option>
                    </select>
                </td>
                <td class="price"><b class="sm">単価最大7桁</b><input type="text" name="単価" value="500"></td>
                <td class="tax">
                    <b class="sm">消費税<br>区分</b>
                    <select>
                        <option>税込</option>
                        <option selected>税込</option>
                        <option>税込</option>
                    </select>
                </td>
                <td class="sales_tax"><b class="sm">消費税率</b><input type="text" name="単価" value="0"><span>%</span></td>
                <td class="d_cate">
                    <b class="sm">控除区分</b>
                    <select>
                        <option>控除対象外</option>
                        <option selected>控除対象外</option>
                        <option>控除対象外</option>
                    </select>
                </td>
                <td>
                    <p class="list_delete">Delete</p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="type">
                    <b class="sm">種類</b>
                    <select>
                        <option>食事朝</option>
                        <option>食事夕</option>
                        <option selected>自費</option>
                    </select>
                </td>
                <td class="item">
                    <b class="sm">項目名称</b>
                    <select>
                        <option>朝食代(刻み食・ミキサー食)</option>
                        <option>夕食</option>
                        <option selected>訪看サービス自費(交通費含む・1時間未満)</option>
                    </select>
                </td>
                <td class="price"><b class="sm">単価最大7桁</b><input type="text" name="単価" value="500"></td>
                <td class="tax">
                    <b class="sm">消費税<br>区分</b>
                    <select>
                        <option>税込</option>
                        <option>税込</option>
                        <option selected>税込</option>
                    </select>
                </td>
                <td class="sales_tax"><input type="text" name="単価" value="0"><span>%</span></td>
                <td class="d_cate">
                    <b class="sm">控除区分</b>
                    <select>
                        <option>控除対象外</option>
                        <option>控除対象外</option>
                        <option selected>控除対象外</option>
                    </select>
                </td>
                <td>
                    <p class="list_delete">Delete</p>
                </td>
                <td>
                    <p class="add_btn">+</p>
                </td>
            </tr>
        </table>
        <p class="add_btn">+</p>
    </div>
    <div class="s_constrols">
        <p><span class="modal_close btn cancel">キャンセル</span></p>
        <p><span class="btn save">保存</span></p>
    </div>
    <div class="update">
        最終更新:
        <span class="time"></span>
        <span class="person"></span>
    </div>
</div>