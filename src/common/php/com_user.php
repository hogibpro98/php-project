<?php
//=======================================================================
// ログイン要求、ログイン状態判定ファイル
//=======================================================================

/*-- 初期値、パラメータ ------------------------------*/

// ログイン画面メッセージ初期化
$loginMsg = NULL;

// URI配列
$uriAry = explode('/', $server['requestUri']);

// アカウント、パスワード
$loginId   = h(filter_input(INPUT_POST,'loginId'));
$loginPass = h(filter_input(INPUT_POST,'loginPass'));

// ログインボタン、ログアウトボタン
$btnLogin  = h(filter_input(INPUT_POST,'btnLogin'));
$btnLogout = h(filter_input(INPUT_POST,'btnLogout'));

// ★デバッグ用★
//$loginUser['unique_id'] = 'stf0001';
//$loginUser['name']      = 'テストスタッフ';
//$loginUser['xxxx']      = 'xxxxxxxxxxxxxxxxx';
//
//$loginUser['place']['001']  = '名称①';
//$loginUser['place']['002']  = '名称②';
//$loginUser['office']['001'] = '名称①';
//$loginUser['office']['002'] = '名称②';
//$_SESSION['login'] = $loginUser;

/*-- ログイン要求処理(ログインページ) ----------------*/
if ($server['requestUri'] === '/'
    || $server['requestUri'] === '/index.php'){
    
    // ボタン押下時
    if ($btnLogin){
        
        // 検索条件設定
        $where = array();
        $where['delete_flg'] = 0;
        $where['account']    = $loginId;
        $where['password']   = $loginPass;
        $staffAry = select('mst_staff', '*', $where);

        // トップページへ遷移
        if ($staffAry){
            
            // 情報の特定
            $staffInfo = $staffAry[0];
            
            // 氏名
            $staffInfo['name'] = $staffInfo['last_name'].' '.$staffInfo['first_name'];
            
            // 拠点リスト、事業所リストの初期化
            $staffInfo['place']  = array();
            $staffInfo['office'] = array();
            $_SESSION['place']   = NULL;
            
            // 所属情報の取得
            $where = array();
            $where['delete_flg'] = 0;
            $where['staff_id'] = $staffInfo['unique_id'];
            $target = 'place_id,place_name,office1_id,office1_name,office2_id,office2_name';
            $temp = select('mst_staff_office',$target,$where);
            foreach ($temp as $val){
                $plcId  = $val['place_id'];
                if($plcId){
                    $staffInfo['place'][$plcId] = $val['place_name'];
                    $_SESSION['place'] = $plcId;
                }
                $ofcId1 = $val['office1_id'];
                if($ofcId1){
                    $staffInfo['office'][$ofcId1] = $val['office1_name'];
                }
                $ofcId2 = $val['office2_id'];
                if($ofcId2){
                    $staffInfo['office'][$ofcId2] = $val['office2_name'];
                }
            }
            
            // 権限により全拠点、全事業所を対象とする
            if ($staffInfo['type'] == 'システム管理者' || $staffInfo['type'] == '法人管理者'){
                $staffInfo['place']  = getPlaceList();
                $staffInfo['office'] = getOfficeList();
            }
            
            // 初期化してセッションへ格納
            session_regenerate_id(TRUE);
            $_SESSION['login'] = $staffInfo;
            
            // 画面遷移先
            nextPage(TOP_PAGE);

        //該当id/pass無し
        } else {
            $loginMsg = 'ログイン情報に誤りがあります';
            $_SESSION['notice']['error'] = array();
            $_SESSION['notice']['error'][] = 'ログイン情報に誤りがあります';
        }
    }

/*-- ログイン不要領域 --------------------------------*/
} else if ($uriAry[1] === 'debug' || $uriAry[1] === 'tool'){
    $loginUser = !empty($_SESSION['login'])? $_SESSION['login']: null;
    if (!$loginUser){
        $loginUser['unique_id'] = 'system';
        $loginUser['name']      = 'テストスタッフ';
    }

/*-- ログイン不要領域 --------------------------------*/
} else {
    $loginUser = !empty($_SESSION['login']) ? $_SESSION['login'] : NULL;
    if (!$loginUser){
        nextPage();
    }

}

/*-- ログアウト処理 ----------------------------------*/
if ($btnLogout){
    $_SESSION = array();
    session_destroy();
    nextPage();
}

    /**
     * $page へ遷移する
     *
     * @param string $page 遷移先ページ
     * @return void
     */
function nextPage(string $page = LOGIN_PAGE)
{
    header('Location:'. $page);
    exit;
}
