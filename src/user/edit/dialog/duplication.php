<?php
/* ===================================================
 * スタッフ検索モーダル
 * ===================================================
 */

/* ===================================================
 * 初期処理
 * ===================================================
 */

/* --共通ファイル呼び出し------------------------------------- */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/* --変数定義------------------------------------------------- */
// 初期化
$err = array();
$_SESSION['notice']['error'] = array();
$dispData = array();
$tgtData = array();

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// 拠点ID
$placeId = filter_input(INPUT_GET, 'place');
if (!$placeId) {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
}

// 利用者(外部ID)取得
$userId = filter_input(INPUT_GET, 'user_id');

// 利用者(姓)カナ取得
$lastKana = h(filter_input(INPUT_GET, 'last_kana'));

// 利用者(名)カナ取得
$firstKana = h(filter_input(INPUT_GET, 'first_kana'));

// 生年月日取得
$birthday = filter_input(INPUT_GET, 'birthday');

/* -- 更新用パラメータ --------------------------------------- */

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ---------------------------------------- */

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- マスタ関連 -------------------------------------------- */

/* -- データ取得 -------------------------------------------- */

// 利用者ID重複
if (!empty($userId)) {
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $userId;
    $temp = select('mst_user', '*', $where);
    $cnkCnt = !empty($dispData['standard']['unique_id']) ? 1 : 0;
    if (count($temp) > $cnkCnt) {
        $dplIcon['other_id'] = true;
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $tgtData[$tgtId] = $val;
        }
    }
}
// カナ名・生年月日重複
if (!empty($lastKana) || !empty($firstKana) || !empty($birthday)) {
    $where = array();
    $where['delete_flg'] = 0;
    $where['last_kana'] = $lastKana;
    $where['first_kana'] = $firstKana;
    $where['birthday'] = $birthday;
    $temp = select('mst_user', '*', $where);
    $cnkCnt = !empty($dispData['standard']['unique_id']) ? 1 : 0;
    if (count($temp) > $cnkCnt) {
        $dplIcon['birthday'] = true;
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $val['kana_name'] = $val['last_kana'] . ' ' . $val['first_kana'];
            $val['office_name'] = getOfficeName($tgtId);
            $tgtData[$tgtId] = $val;
        }
    }
}
/* -- その他計画関連 ------------------------------ */

/* -- 画面表示データ格納 ---------------------------- */

$dispData = $tgtData;
?>

<?php if ($dispData) : ?>
    <div class="dynamic_modal cont_user-dup cancel_act" style="left: 50%;top: 50%;transform: translateX(-50%) translateY(-50%); display:block; z-index: 999;">
        <div class="tit">重複利用者一覧</div>
        <div class="modal_close close close_part">✕<span class="modal_close">閉じる</span></div>
        <div class="cont_list">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>氏名(カナ)</th>
                        <th>生年月日</th>
                        <th>契約事業所名</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dispData as $tgtId => $val): ?>
                        <tr>
                            <td><button type="button"><a href="/user/edit/?id=<?= $tgtId ?>">選択</a></button></td>
                            <td><?= $val['kana_name'] ?></td>
                            <td><?= $val['birthday'] ?></td>
                            <td><?= $val['office_name'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script>
            $(function () {
                // ダイアログクローズ
                $(".modal_close").on("click", function () {
                    // windowを閉じる
                    $(".dynamic_modal").remove();
                });
            });
        </script>
    </div>
<?php endif; ?>