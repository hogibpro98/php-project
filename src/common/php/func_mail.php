<?php

//=======================================================================
//   メール関数群
//=======================================================================

/* =======================================================================
 * 指定文字変換関数
 * =======================================================================
 *
 *   [機能]
 *     メール用文字化け対策として、指定文字の変換をする
 *
 *   [引数]
 *     ① 変換前文字列
 *
 *   [戻り値]
 *     $res = 変換後の文字列
 *
 * -----------------------------------------------------------------------
 */
function mailStrReplace($string)
{

    /* -- 初期値 ----------------------------------------------*/
    $res = null;
    //変換前の文字
    $replaceStr['before'] = array('①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩','№','㈲','㈱','髙');
    //変換後の文字
    $replaceStr['after'] = array('(1)','(2)','(3)','(4)','(5)','(6)','(7)','(8)','(9)','(10)','No.','（有）','（株）','高');

    /* -- 条件指定、DB取得 ------------------------------------*/

    /* -- データ変換 ------------------------------------------*/
    return str_replace($replaceStr['before'], $replaceStr['after'], $string);

    /* -- データ返却 ------------------------------------------*/
}
/* =======================================================================
 * メール送信関数（php既存のmail関数使用）
 * =======================================================================
 *
 *   [機能]
 *     メール送信
 *
 *   [from com_ini]
 *          ENV_MAIL = (文字列)運営アドレス
 *          ENV_NAME = (文字列)運営表示名称
 *
 *   [引数]
 *     ① メール設定配列
 *        <共通>
 *          ['address']['to']       = (配列)送信アドレス
 *          ['address']['cc']       = (配列)送信アドレス
 *          ['address']['bcc']      = (配列)送信アドレス
 *          ['type']                = (文字列)メール種類
 *          ['shop']                = (文字列)店舗ID
 *
 *     ② メール補完用データ
 *          [key][seq] = (文字列)data
 *             key1 : data1
 *             key2 : data2
 *             key3 : data3
 *
 *   [戻り値]
 *     ※成功時 $res = NULL
 *     ※失敗時 $res = エラーメッセージ
 *
 * -----------------------------------------------------------------------
 */
function sendMail($config, $tgtData)
{

    /* -- 初期値 ----------------------------------------------*/
    $res     = null;
    $toAry   = array();
    $ccAry   = array();
    $bccAry  = array();
    $header  = '';
    $subject = '';
    $body    = '';

    /*--メール内容設定--------------------------------------------------------*/

    // タイトル
    $subject = '=?iso-2022-jp?B?' . base64_encode(mb_convert_encoding($config['type'], 'JIS', 'UTF-8')) . "?=";

    // 本文
    foreach ($tgtData as $key => $val) {
        $body .= $body ? "\r\n" : '';
        $body .= !is_int($key) ? '   [' . $key . '] : ' . $val
                : $val;
    }
    $body = mailStrReplace($body);

    // エンコード調整
    mb_language('ja');
    $defEncode = mb_internal_encoding();
    if ($defEncode != 'UTF-8') {
        mb_internal_encoding('UTF-8');
    }

    /*--メール宛先設定--------------------------------------------------------*/
    foreach ($config['address'] as $addressType => $adressAry) {
        switch ($addressType) {
            case 'to':
                $toAry    = $adressAry;
                break;
            case 'cc':
                $ccAry  = $adressAry;
                break;
            case 'bcc':
                $bccAry = $adressAry;
                break;
        }
    }

    /*--ヘッダー設定----------------------------------------------------------*/

    // メールヘッダ
    $header .= "From: " . mb_encode_mimeheader(ENV_NAME, "iso-2022-jp") . " <" . ENV_MAIL . ">" . "\n";
    $header .= "CC: " . implode(',', $ccAry) . "\n";
    $header .= "BCC: " . implode(',', $bccAry) . "\n";
    $header .= "Reply-To: " . ENV_MAIL . "\n";
    $header .= "Content-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/" . phpversion();

    /*--メール送信------------------------------------------------------------*/
    foreach ($toAry as $to) {
        // ------------------------------------
        // com_ini定義
        // SENDMAIL = 0 : 送信しない
        // SENDMAIL = 1 : 送信する
        // ------------------------------------
        if (SENDMAIL) {
            // $res = mail($to,$subject,$body,$header,'-f '.ENV_MAIL);

            $sslConf = array();
            $sslConf['ssl']['verify_peer']       = false;
            $sslConf['ssl']['verify_peer_name']  = false;
            $sslConf['ssl']['allow_self_signed'] = false;

            mb_language('uni');
            mb_internal_encoding('UTF-8');
            $mail = new PHPMailer(true);
            $mail->CharSet = 'utf-8';
            $mail->isSMTP();                    // SMTPの使用宣言
            $mail->Host       = ENV_MAILHOST;   // SMTPサーバーを指定
            $mail->SMTPAuth   = true;           // SMTP authenticationを有効化
            $mail->Username   = ENV_MAIL;       // SMTPサーバーのユーザ名
            $mail->Password   = ENV_MAILPASS;   // SMTPサーバーのパスワード
            $mail->SMTPSecure = ENV_MAILSecure; // 暗号化を有効（tls or ssl）無効の場合はfalse
            $mail->Port       = ENV_MAILPort;   // TCPポートを指定（tlsの場合は465や587）
            $mail->setFrom(ENV_MAIL, ENV_NAME); // 送信者
            $mail->addAddress($to);             // 宛先
            foreach ($ccAry as $address) {
                $mail->addCC($address);  // CC宛先
            }
            foreach ($bccAry as $address) {
                $mail->addBCC($address); // BCC宛先
            }
            $mail->SMTPOptions = $sslConf;
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
        }
    }

    return $res;
}
