<?php

//error_reporting(E_ALL | E_STRICT);

##-----------------------------------------------------------------------------------------------------------------##
#
#  PHPメールプログラム　フリー版 最終更新日2018/07/27
#　改造や改変は自己責任で行ってください。
#
#  HP: http://www.php-factory.net/
#
#  重要！！
#  サイトでチェックボックスを使用する場合のみですが。。。
#  チェックボックスを使用する場合はinputタグに記述するname属性の値を必ず配列の形にしてください。
#  例　name="xxx[]"
#  として下さい。
#
##-----------------------------------------------------------------------------------------------------------------##
if ( version_compare( PHP_VERSION, '5.1.0', '>=' ) ) {//PHP5.1.0以上の場合のみタイムゾーンを定義
    date_default_timezone_set( 'Asia/Tokyo' );//タイムゾーンの設定（日本以外の場合には適宜設定ください）
}

/*-------------------------------------------------------------------------------------------------------------------
* ★以下設定時の注意点　
* ・メールアドレスのname属性の値が「email」ではない場合、以下必須設定箇所の「$email」の値も変更下さい。
* ・name属性の値に半角スペースは使用できません。
-------------------------------------------------------------------------------------------------------------------*/


class Mail {
    // 管理者のメールアドレス
    // ※メールを受け取るメールアドレス(複数指定する場合は「,」で区切ってください 例 $to = "aa@aa.aa,bb@bb.bb";)
    private $adminMailAddress = "info@city-craft.co.jp";

    // 管理者宛に送信されるメールのタイトル（件名）
    private $adminSubject = "ホームページのお問い合わせ";

    // 自動返信メールの送信元メールアドレス
    // 必ず実在するメールアドレスでかつ出来る限り設置先サイトのドメインと同じドメインのメールアドレスとすることを強く推奨します
    private $from = "info@city-craft.co.jp";

    // 必須入力項目を設定する(する=1, しない=0)
    private $isRequireCheck = 1;

    // フォームのメールアドレス入力箇所のname属性の値（name="○○"　の○○部分）
    private $email = "メールアドレス";

    /* 必須入力項目(入力フォームで指定したname属性の値を指定してください。（上記で1を設定した場合のみ）
    値はシングルクォーテーションで囲み、複数の場合はカンマで区切ってください。フォーム側と順番を合わせると良いです。
    配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。*/
    private $require = array(
        'お名前',
        'メールアドレス',
        'お問い合わせ内容',
    );

    // フォーム側の「名前」箇所のname属性の値
    // ※自動返信メールの「○○様」の表示で使用します。
    // 指定しない、または存在しない場合は、○○様と表示されないだけです。あえて無効にしてもOK
    private $dsp_name = 'お名前';



    // 送信確認画面の表示(する=1, しない=0)
    private $isConfirmPage = 0;

    // 送信完了後に自動的に指定のページ(サンクスページなど)に移動する(する=1, しない=0)
    // CV率を解析したい場合などはサンクスページを別途用意し、URLをこの下の項目で指定してください。
    // 0にすると、デフォルトの送信完了画面が表示されます。
    private $jumpPage = 0;

    // 送信完了後に表示するページURL（上記で1を設定した場合のみ）※httpから始まるURLで指定ください。（相対パスでも基本的には問題ないです）
    private $thanksPage = "./thanks.html";

    // サイトのトップページのURL
    // ※デフォルトでは送信完了後に「トップページへ戻る」ボタンが表示されますので
    private $site_top = "./";




    // セキュリティ、スパム防止のための設定　------------------------------------

    // スパム防止のためのリファラチェック（フォーム側とこのファイルが同一ドメインであるかどうかのチェック）(する=1, しない=0)
    // ※有効にするにはこのファイルとフォームのページが同一ドメイン内にある必要があります
    private $Referer_check = 0;

    // リファラチェックを「する」場合のドメイン ※設置するサイトのドメインを指定して下さい。
    // もしこの設定が間違っている場合は送信テストですぐに気付けます。
    private $Referer_check_domain = "php-factory.net";

    // セッションによるワンタイムトークン（CSRF対策、及びスパム防止）(する=1, しない=0)
    // ※ただし、この機能を使う場合は↓の送信確認画面の表示が必須です。（デフォルトではON（1）になっています）
    // ※【重要】ガラケーは機種によってはクッキーが使えないためガラケーの利用も想定してる場合は「0」（OFF）にして下さい（PC、スマホは問題ないです）
    private $isUseOneTimeToken = 1;

    // セキュリティ、スパム防止のための設定　ここまで　------------------------------------




    //---------------------- 任意設定　以下は必要に応じて設定してください ------------------------

    // 管理者宛のメールの差出人を、送信者のメールアドレスにする(する=1, しない=0)
    // する場合は、メール入力欄のname属性の値を「$email」で指定した値にしてください。
    private $isUseUserMailAddressForAdminFrom = 1;

    // Bccで送るメールアドレス(複数指定する場合は「,」で区切ってください 例 $BccMail = "aa@aa.aa,bb@bb.bb";)
    private $BccMail = "";




    //----------------------------------------------------------------------
    //  自動返信メール設定(START)
    //----------------------------------------------------------------------

    // 差出人に送信内容確認メール（自動返信メール）を送る(送る=1, 送らない=0)
    // 送る場合は、フォーム側のメール入力欄のname属性の値が上記「$email」で指定した値と同じである必要があります
    private $isSendMailToUser = 1;

    // 自動返信メールの送信者欄に表示される名前　※あなたの名前や会社名など（もし自動返信メールの送信者名が文字化けする場合ここは空にしてください）
    private $userFrom = "";

    // 差出人に送信確認メールを送る場合のメールのタイトル（上記で1を設定した場合のみ）
    private $userSubject = "送信ありがとうございました";

    // 自動返信メールの冒頭の文言 ※日本語部分のみ変更可
    private $messageForUser = <<< TEXT

お問い合わせありがとうございました。
早急にご返信致しますので今しばらくお待ちください。

送信内容は以下になります。

TEXT;


    // 自動返信メールに署名（フッター）を表示(する=1, しない=0)
    // ※管理者宛にも表示されます。
    private $mailFooterDsp = 0;

    //上記で「1」を選択時に表示する署名（フッター）（FOOTER～FOOTER;の間に記述してください）
    private $mailSignature = <<< FOOTER

──────────────────────
株式会社シティクラフト
〒460-0003
名古屋市中区錦二丁目19-18　丸三証券名古屋ビル 6F
TEL：052-228-0970　FAX：052-228-0971
──────────────────────

FOOTER;

    //----------------------------------------------------------------------
    //  自動返信メール設定(END)
    //----------------------------------------------------------------------






    // メールアドレスの形式チェックを行うかどうか。(する=1, しない=0)
    // ※デフォルトは「する」。特に理由がなければ変更しないで下さい。メール入力欄のname属性の値が上記「$email」で指定した値である必要があります。
    private $mail_check = 1;

    //全角英数字→半角変換を行うかどうか。(する=1, しない=0)
    private $hankaku = 0;

    //全角英数字→半角変換を行う項目のname属性の値（name="○○"の「○○」部分）
    //※複数の場合にはカンマで区切って下さい。（上記で「1」を指定した場合のみ有効）
    //配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。
    private $hankaku_array = array(
        '電話番号',
        '金額'
    );

    //-fオプションによるエンベロープFrom（Return-Path）の設定(する=1, しない=0)　
    //※宛先不明（間違いなどで存在しないアドレス）の場合に 管理者宛に「Mail Delivery System」から「Undelivered Mail Returned to Sender」というメールが届きます。
    //サーバーによっては稀にこの設定が必須の場合もあります。
    //設置サーバーでPHPがセーフモードで動作している場合は使用できませんので送信時にエラーが出たりメールが届かない場合は「0」（OFF）として下さい。
    private $use_envelope = 0;

    //------------------------------- 任意設定ここまで ---------------------------------------------


    // 変数初期化
    private $encode = "UTF-8";//このファイルの文字コード定義（変更不可）

    private $replaceStr;
    private $sendmail = 0;
    private $post_mail = '';
    private $errm = '';
    private $empty_flag = 0;
    private $header = '';

    private $get;
    private $post;
    private $cookie;



    public function __construct() {
        // 以下の変更は知識のある方のみ自己責任でお願いします。

        //----------------------------------------------------------------------
        //  関数実行、変数初期化
        //----------------------------------------------------------------------
        //トークンチェック用のセッションスタート
        if ( $this->isUseOneTimeToken == 1 && $this->isConfirmPage == 1 ) {
            session_name( 'PHPMAILFORMSYSTEM' );
            session_start();
        }

        // header( "Content-Type:text/html;charset=utf-8" );

        $this->setDependentCharacters();
        $this->cleanUpInput();
        $this->refererCheck();
        $this->requireCheck();// 必須チェック実行し返り値を受け取る
        $this->mailAddressCheck();

        if ( ( $this->isConfirmPage == 0 || $this->sendmail == 1 ) && $this->empty_flag != 1 ) {
            $this->checkOneTimeToken();
            $this->send();
        } else if ( $this->isConfirmPage == 1 ) {

            /*　▼▼▼送信確認画面のレイアウト※編集可　オリジナルのデザインも適用可能▼▼▼　*/
            ?>
            <!DOCTYPE HTML>
            <html lang="ja">
            <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
            <meta name="format-detection" content="telephone=no">
            <title>確認画面</title>
            <style type="text/css">
                #formWrap{width:700px;margin:0 auto;color:#555;line-height:120%;font-size:90%}
                table.formTable{width:100%;margin:0 auto;border-collapse:collapse}
                table.formTable td,table.formTable th{border:1px solid #ccc;padding:10px}
                table.formTable th{width:30%;font-weight:400;background:#efefef;text-align:left}
                p.error_messe{margin:5px 0;color:red}

                @media screen and (max-width: 572px) {
                    #formWrap{width:95%;margin:0 auto}
                    table.formTable th,table.formTable td{width:auto;display:block}
                    table.formTable th{margin-top:5px;border-bottom:0}
                    input[type="submit"],input[type="reset"],input[type="button"]{display:block;width:100%;height:40px}
                }

            </style>
            </head>
            <body>

            <!-- ▲ Headerやその他コンテンツなど　※自由に編集可 ▲-->

            <!-- ▼************ 送信内容表示部　※編集は自己責任で ************ ▼-->
            <div id="formWrap">
                <?php if ( $this->empty_flag == 1 ) { ?>
                    <div align="center">
                        <h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
                        <?php echo $this->errm; ?>
                        <br/><br/><input type="button" value=" 前画面に戻る " onClick="history.back()">
                    </div>
                <?php } else { ?>
                    <h3>確認画面</h3>
                    <p align="center">以下の内容で間違いがなければ、「送信する」ボタンを押してください。</p>
                    <form action="<?php echo $this->h( $_SERVER['SCRIPT_NAME'] ); ?>" method="POST">
                        <table class="formTable">
                            <?php

                            echo $this->confirmOutput( $this->post );//入力内容を表示

                            ?>
                        </table>
                        <p align="center">
                            <input type="hidden" name="mail_set" value="confirm_submit">
                            <input type="hidden" name="httpReferer" value="<?php echo $this->h( $_SERVER['HTTP_REFERER'] ); ?>">
                            <input type="submit" value="　送信する　">
                            <input type="button" value="前画面に戻る" onClick="history.back()">
                        </p>
                    </form>
                <?php } ?>
            </div>
            <!-- ▲ *********** 送信内容確認部　※編集は自己責任で ************ ▲-->

            <!-- ▼ Footerその他コンテンツなど　※編集可 ▼-->
            </body>
            </html>
            <?php
            /* ▲▲▲送信確認画面のレイアウト　※オリジナルのデザインも適用可能▲▲▲　*/
        }

        if ( ( $this->jumpPage == 0 && $this->sendmail == 1 ) || ( $this->jumpPage == 0 && ( $this->isConfirmPage == 0 && $this->sendmail == 0 ) ) ) {

            /* ▼▼▼送信完了画面のレイアウト　編集可 ※送信完了後に指定のページに移動しない場合のみ表示▼▼▼　*/ ?>
            <!DOCTYPE HTML>
            <html lang="ja">
            <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
            <meta name="format-detection" content="telephone=no">
            <title>完了画面</title>
            </head>
        <body>
        <div align="center">
            <?php if ( $this->empty_flag == 1 ) { ?>
                <h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
                <div style="color:red">
                    <?php echo $this->errm; ?>
                </div>

                <br/><br/>

                <input type="button" value=" 前画面に戻る " onClick="history.back()">
                </div>
                </body>
                </html>
            <?php } else { ?>

                送信ありがとうございました。<br/>
                送信は正常に完了しました。<br/>
                <br/>

                <a href="<?php echo $this->site_top; ?>">
                    トップページへ戻る&raquo;
                </a>
                </div>

                <!--  CV率を計測する場合ここにAnalyticsコードを貼り付け -->
                </body>
                </html>
                <?php
                /* ▲▲▲送信完了画面のレイアウト 編集可 ※送信完了後に指定のページに移動しない場合のみ表示▲▲▲　*/
            }
        } //確認画面無しの場合の表示、指定のページに移動する設定の場合、エラーチェックで問題が無ければ指定ページヘリダイレクト
        else if ( ( $this->jumpPage == 1 && $this->sendmail == 1 ) || $this->isConfirmPage == 0 ) {
            if ( $this->empty_flag == 1 ) {
                ?>
                <div align="center">
                    <h4>
                        入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。
                    </h4>
                    <div style="color:red">
                        <?php echo $this->errm; ?>
                    </div>
                    <br/>
                    <br/>

                    <input type="button" value=" 前画面に戻る " onClick="history.back()">
                </div>
                <?php
            } else {
                header( "Location: " . $this->thanksPage );
            }
        }
    }

    /*
     *
     */
    function cleanUpInput() {
        if ( isset( $_GET ) ) {
            $this->get = $this->sanitize( $_GET );
        }//NULLバイト除去//

        if ( isset( $_POST ) ) {
            $this->post = $this->sanitize( $_POST );
        }//NULLバイト除去//

        if ( isset( $_COOKIE ) ) {
            $this->cookie = $this->sanitize( $_COOKIE );
        }//NULLバイト除去//

        if ( $this->encode == 'SJIS' ) {
            $this->post = $this->sjisReplace( $this->post );
        }//Shift-JISの場合に誤変換文字の置換実行
    }

    /*
     *
     */
    function mailAddressCheck() {
        //メールアドレスチェック
        if ( empty( $this->errm ) ) {
            foreach ( $this->post as $key => $val ) {
                if ( $val == "confirm_submit" ) {
                    $this->sendmail = 1;
                }

                if ( $key == $this->email ) {
                    $this->post_mail = $this->h( $val );
                }

                if ( $key == $this->email && $this->mail_check == 1 && ! empty( $val ) ) {
                    if ( ! $this->checkMail( $val ) ) {
                        $this->errm       .= "<p class=\"error_messe\">【" . $key . "】はメールアドレスの形式が正しくありません。</p>\n";
                        $this->empty_flag = 1;
                    }
                }
            }
        }
    }

    /*
     *
     */
    function send() {
        // To User
        if ( $this->isSendMailToUser == 1 ) {
            $userSubject = "=?iso-2022-jp?B?" . base64_encode( mb_convert_encoding( $this->userSubject, "JIS", $this->encode ) ) . "?=";
            $userBody    = $this->mailToUser( $this->post );
            $userHeader  = $this->userHeader();
        }

        // To Admin
        $adminSubject = "=?iso-2022-jp?B?" . base64_encode( mb_convert_encoding( $this->adminSubject, "JIS", $this->encode ) ) . "?=";
        $adminBody    = $this->mailToAdmin( $this->post );
        $adminHeader  = $this->adminHeader();

        // -f オプションによるエンベロープFrom（Return-Path）の設定(safe_modeがOFFの場合かつ上記設定がONの場合のみ実施)
        if ( $this->use_envelope == 0 ) {
            mail( $this->adminMailAddress, $adminSubject, $adminBody, $adminHeader );

            if ( $this->isSendMailToUser == 1 && ! empty( $this->post_mail ) ) {
                mail( $this->post_mail, $userSubject, $userBody, $userHeader );
            }
        } else {
            mail( $this->adminMailAddress, $adminSubject, $adminBody, $adminHeader, '-f' . $this->from );

            if ( $this->isSendMailToUser == 1 && ! empty( $this->post_mail ) ) {
                mail( $this->post_mail, $userSubject, $userBody, $userHeader, '-f' . $this->from );
            }
        }
    }

    /*
     *
     */
    function checkMail( $str ) {
        $mailaddress_array = explode( '@', $str );

        if ( preg_match( "/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-zA-Z]+(\.[!#%&\-_0-9a-zA-Z]+)+$/", "$str" ) && count( $mailaddress_array ) == 2 ) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 送信メールにPOSTデータをセットする関数
     */
    function postToMail( $arr ) {
        $resArray = '';

        foreach ( $arr as $key => $val ) {
            $out = '';

            if ( is_array( $val ) ) {
                foreach ( $val as $key02 => $item ) {
                    //連結項目の処理
                    if ( is_array( $item ) ) {
                        $out .= $this->connect2val( $item );
                    } else {
                        $out .= $item . ', ';
                    }
                }
                $out = rtrim( $out, ', ' );

            } else {
                $out = $val;
            }//チェックボックス（配列）追記ここまで

            if ( get_magic_quotes_gpc() ) {
                $out = stripslashes( $out );
            }

            //全角→半角変換
            if ( $this->hankaku == 1 ) {
                $out = $this->zenkaku2hankaku( $key, $out );
            }

            if ( $out != "confirm_submit" && $key != "httpReferer" ) {
                $resArray .= "【 " . $this->h( $key ) . " 】 " . $this->h( $out ) . "\n";
            }
        }

        return $resArray;
    }

    /*
     * 管理者宛送信メールヘッダ
     */
    function adminHeader() {
        $result = '';

        if ( $this->isUseUserMailAddressForAdminFrom == 1 && ! empty( $this->post_mail ) ) {
            $result = "From: $this->post_mail\n";

            if ( $this->BccMail != '' ) {
                $result .= "Bcc: $this->BccMail\n";
            }

            $result .= "Reply-To: " . $this->post_mail . "\n";
        } else {
            if ( $this->BccMail != '' ) {
                $result = "Bcc: $this->BccMail\n";
            }

            $result .= "Reply-To: " . $this->adminMailAddress . "\n";
        }

        $result .= "Content-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/" . phpversion();

        return $result;
    }

    /*
     * 管理者宛送信メールボディ
     */
    function mailToAdmin( $arr ) {
        $adminBody = "「" . $this->adminSubject . "」からメールが届きました\n\n";
        $adminBody .= "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
        $adminBody .= $this->postToMail( $arr );//POSTデータを関数からセット
        $adminBody .= "\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
        $adminBody .= "送信された日時：" . date( "Y/m/d (D) H:i:s", time() ) . "\n";
        $adminBody .= "送信者のIPアドレス：" . @$_SERVER["REMOTE_ADDR"] . "\n";
        $adminBody .= "送信者のホスト名：" . getHostByAddr( getenv( 'REMOTE_ADDR' ) ) . "\n";

        if ( $this->isConfirmPage != 1 ) {
            $adminBody .= "問い合わせのページURL：" . @$_SERVER['HTTP_REFERER'] . "\n";
        } else {
            $adminBody .= "問い合わせのページURL：" . @$arr['httpReferer'] . "\n";
        }

        if ( $this->mailFooterDsp == 1 ) {
            $adminBody .= $this->mailSignature;
        }

        return mb_convert_encoding( $adminBody, "JIS", $this->encode );
    }

    /*
     * ユーザ宛送信メールヘッダ
     */
    function userHeader() {
        $result = "From: ";

        if ( ! empty( $this->userFrom ) ) {
            $default_internal_encode = mb_internal_encoding();

            if ( $default_internal_encode != $this->encode ) {
                mb_internal_encoding( $this->encode );
            }

            $result .= mb_encode_mimeheader( $this->userFrom ) . " <" . $this->adminMailAddress . ">\nReply-To: " . $this->adminMailAddress;
        } else {
            $result .= "$this->adminMailAddress\nReply-To: " . $this->adminMailAddress;
        }

        $result .= "\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/" . phpversion();

        return $result;
    }

    /*
     * ユーザ宛送信メールボディ
     */
    function mailToUser( $arr ) {
        $userBody = '';

        if ( isset( $arr[ $this->dsp_name ] ) ) {
            $userBody = $this->h( $arr[ $this->dsp_name ] ) . " 様\n";
        }

        $userBody .= $this->messageForUser;
        $userBody .= "\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
        $userBody .= $this->postToMail( $arr );//POSTデータを関数からセット
        $userBody .= "\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
        $userBody .= "送信日時：" . date( "Y/m/d (D) H:i:s", time() ) . "\n";

        if ( $this->mailFooterDsp == 1 ) {
            $userBody .= $this->mailSignature;
        }

        return mb_convert_encoding( $userBody, "JIS", $this->encode );
    }

    /*
     * 必須チェック関数
     */
    function requireCheck() {
        if ( $this->isRequireCheck === 0 ) {
            return false;
        }

        foreach ( $this->require as $requireVal ) {
            $existsFalg = '';

            foreach ( $this->post as $key => $val ) {
                if ( $key == $requireVal ) {

                    //連結指定の項目（配列）のための必須チェック
                    if ( is_array( $val ) ) {
                        $connectEmpty = 0;

                        foreach ( $val as $kk => $vv ) {
                            if ( is_array( $vv ) ) {
                                foreach ( $vv as $kk02 => $vv02 ) {
                                    if ( $vv02 == '' ) {
                                        $connectEmpty ++;
                                    }
                                }
                            }

                        }

                        if ( $connectEmpty > 0 ) {
                            $this->errm       .= "<p class=\"error_messe\">【" . $this->h( $key ) . "】は必須項目です。</p>\n";
                            $this->empty_flag = 1;
                        }
                    } //デフォルト必須チェック
                    elseif ( $val == '' ) {
                        $this->errm       .= "<p class=\"error_messe\">【" . $this->h( $key ) . "】は必須項目です。</p>\n";
                        $this->empty_flag = 1;
                    }

                    $existsFalg = 1;
                    break;
                }

            }

            if ( $existsFalg != 1 ) {
                $this->errm       .= "<p class=\"error_messe\">【" . $requireVal . "】が未選択です。</p>\n";
                $this->empty_flag = 1;
            }
        }
    }

    /*
     * 確認画面の入力内容出力用関数
     */
    function confirmOutput( $arr ) {
        $html = '';

        foreach ( $arr as $key => $val ) {
            $out = '';

            if ( is_array( $val ) ) {
                foreach ( $val as $key02 => $item ) {
                    //連結項目の処理
                    if ( is_array( $item ) ) {
                        $out .= $this->connect2val( $item );
                    } else {
                        $out .= $item . ', ';
                    }
                }

                $out = rtrim( $out, ', ' );

            } else {
                $out = $val;
            }//チェックボックス（配列）追記ここまで

            if ( get_magic_quotes_gpc() ) {
                $out = stripslashes( $out );
            }

            $out = nl2br( $this->h( $out ) );//※追記 改行コードを<br>タグに変換
            $key = $this->h( $key );
            $out = str_replace( $this->replaceStr['before'], $this->replaceStr['after'], $out );//機種依存文字の置換処理

            //全角→半角変換
            if ( $this->hankaku == 1 ) {
                $out = $this->zenkaku2hankaku( $key, $out );
            }

            $html .= "<tr><th>" . $key . "</th><td>" . $out;
            $html .= '<input type="hidden" name="' . $key . '" value="' . str_replace( array(
                    "<br />",
                    "<br>"
                ), "", $out ) . '" />';
            $html .= "</td></tr>\n";
        }

        //トークンをセット
        if ( $this->isUseOneTimeToken == 1 && $this->isConfirmPage == 1 ) {
            $token                      = sha1( uniqid( mt_rand(), true ) );
            $_SESSION['mailform_token'] = $token;
            $html                       .= '<input type="hidden" name="mailform_token" value="' . $token . '" />';
        }

        return $html;
    }

    // 機種依存文字の変換
    // たとえば㈱（かっこ株）や①（丸1）、その他特殊な記号や特殊な漢字などは変換できずに「？」と表示されます。それを回避するための機能です。
    // 確認画面表示時に置換処理されます。「変換前の文字」が「変換後の文字」に変換され、送信メール内でも変換された状態で送信されます。（たとえば「㈱」の場合、「（株）」に変換されます）
    // 必要に応じて自由に追加して下さい。ただし、変換前の文字と変換後の文字の順番と数は必ず合わせる必要がありますのでご注意下さい。
    function setDependentCharacters() {
        //変換前の文字
        $this->replaceStr['before'] = array( '①', '②', '③', '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '№', '㈲', '㈱', '髙' );

        //変換後の文字
        $this->replaceStr['after'] = array( '(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)', '(10)', 'No.', '（有）', '（株）', '高' );
    }

    /*
     *
     */
    function refererCheck() {
        if ( strpos( $_SERVER['HTTP_REFERER'], '://localhost' ) !== false ) {
            return true;
        }

        if ( $this->Referer_check == 1 && ! empty( $this->Referer_check_domain ) ) {
            if ( strpos( $_SERVER['HTTP_REFERER'], $this->Referer_check_domain ) === false ) {
                return exit( 'リファラチェックエラー。フォームページのドメインとこのファイルのドメインが一致しません' );
            }
        }
    }

    /*
     *
     */
    function checkOneTimeToken() {
        //トークンチェック（CSRF対策）※確認画面がONの場合のみ実施
        if ( $this->isUseOneTimeToken == 1 && $this->isConfirmPage == 1 ) {
            if ( empty( $_SESSION['mailform_token'] ) || ( $_SESSION['mailform_token'] !== $this->post['mailform_token'] ) ) {
                exit( 'ページ遷移が不正です' );
            }

            if ( isset( $_SESSION['mailform_token'] ) ) {
                unset( $_SESSION['mailform_token'] );
            }//トークン破棄

            if ( isset( $this->post['mailform_token'] ) ) {
                unset( $this->post['mailform_token'] );
            }//トークン破棄
        }
    }

    /*
     *
     */
    function h( $string ) {
        return htmlspecialchars( $string, ENT_QUOTES, $this->encode );
    }

    function sanitize( $arr ) {
        if ( is_array( $arr ) ) {
            return array_map( array( $this, 'sanitize' ), $arr );
        }

        return str_replace( "\0", "", $arr );
    }

    /*
     * Shift-JISの場合に誤変換文字の置換関数
     */
    function sjisReplace( $arr ) {
        foreach ( $arr as $key => $val ) {
            $key              = str_replace( '＼', 'ー', $key );
            $resArray[ $key ] = $val;
        }

        return $resArray;
    }

    /*
     * 全角→半角変換
     */
    function zenkaku2hankaku( $key, $out ) {
        if ( is_array( $this->hankaku_array ) && function_exists( 'mb_convert_kana' ) ) {
            foreach ( $this->hankaku_array as $hankaku_array_val ) {
                if ( $key == $hankaku_array_val ) {
                    $out = mb_convert_kana( $out, 'a', $this->encode );
                }
            }
        }

        return $out;
    }

    /*
     * 配列連結の処理
     */
    function connect2val( $arr ) {
        $out = '';

        foreach ( $arr as $key => $val ) {
            if ( $key === 0 || $val == '' ) {//配列が未記入（0）、または内容が空のの場合には連結文字を付加しない（型まで調べる必要あり）
                $key = '';
            } elseif ( strpos( $key, "円" ) !== false && $val != '' && preg_match( "/^[0-9]+$/", $val ) ) {
                $val = number_format( $val );//金額の場合には3桁ごとにカンマを追加
            }

            $out .= $val . $key;
        }

        return $out;
    }
}

new Mail();

