<?php require_once(dirname(__FILE__) . "/php/body_image.php"); ?>
<!DOCTYPE html>
<html lang="ja">

    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!-- <link href="./css/draw.css" rel="stylesheet"> -->
        <!--CONTENT-->
        <title>看多機記録</title>

        <style>
            .layer-wrap>div.canvas-container {
                position: absolute !important;
            }
        </style>
    </head>

    <body>
        <div id="wrapper">
            <div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <form action="" name="myform" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
                        <h2 class="tit_sm">看多機記録(身体図編集)</h2>
                        <div id="kantaki" class="nursing">
                            <div class="wrap">
                                <div id="canvas-container" class=" box_wrap layer-wrap" style="width:490;height:742;">
                                    <canvas id="canvas" width="490" height="742" style="border:5px solid; border-color:#999999;"></canvas>
                                </div>
                                <div id="cursor" class="hideDotCursor">
                                </div>
                            </div>
                        </div>
                        <div><p style="height:300px;"></p></div>
                        <div class="fixed_navi patient_navi">
                            <div class="box">
                                <div class="btn back pc">
                                    <button type="submit" name="btnReturn" value="return">看多機記録にもどる</button>
                                </div>
                                <div class="controls">
                                    <input type="hidden" id="uniqueId" value="<?= $tgtData['unique_id'] ?>">
                                    <button type="button" class="btn save" name="btnEntry" value="<?= $keyId; ?>" onclick="OnButtonClick(canvas);">保存</button>
                                </div>
                            </div>
                            <div>
                                <img id="canvasImg" src="" style="width:0;height:0;">
                            </div>
                        </div>
                    </form>
                </article>
                <!--CONTENT-->
            </div>
        </div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
        <script src=" /common/js/fabric.min.js"></script>
        <script>
                                        var canvas;
                                        // オブジェクトの定義
                                        let newRect = fabric.Rect; // 四角形
                                        let newCircle = fabric.Circle; // 円形
                                        let newText = fabric.Text; // 文字
                                        let newEllipse = fabric.Ellipse;
                                        const OBJ_NAME = "objNo";
                                        var cnt;

                                        // function init() {
                                        canvas = new fabric.Canvas('canvas');
                                        canvas.isDrawingMode = false;
                                        canvas.selection = true;
                                        canvas.border = "4px solid";

                                        // uniqueIDの取得
                                        var uniqueId = document.getElementById('uniqueId').value;

                                        // DBのJSONデータを取得する
                                        var data = '<?= $tgtData['image_json'] ?>';
                                        if (data) {
                                            canvas.loadFromJSON(data).renderAll();
                                            // 初期化
                                            newEllipse = undefined;
                                            newText = undefined;
                                        } else {
                                            // 画像読込み
                                            var img_path = '/common/image/sub/body_img.png';
                                            fabric.Image.fromURL(img_path, (img) => {
                                                canvas.setDimensions({
                                                    width: img.width,
                                                    height: img.height
                                                });
                                                canvas.setBackgroundImage(img, () => {
                                                    canvas.requestRenderAll();
                                                });
                                            });
                                        }

                                        canvas.on('mouse:down', (options) => {
                                            if (!options.pointer) {
                                                return;
                                            }
                                            if (options.target === null) {

                                                // 楕円の描画
                                                newEllipse = new fabric.Ellipse({
                                                    id: OBJ_NAME + cnt, //ここでidを設定する
                                                    left: options.pointer.x, //左上角相当部分（赤点）の左
                                                    top: options.pointer.y, //左上角相当部分（赤点）の上
                                                    rx: 20, //水平半径
                                                    ry: 15, //垂直半径
                                                    strokeWidth: 1, //線の太さ
                                                    stroke: '#FFAA00', //線の色
                                                    fill: 'rgba(214, 145, 5, .32)', //塗潰しの色
                                                    angle: 0, //角度
                                                    borderColor: 'red',
                                                    borderScaleFactor: 2,
                                                    selectable: true
                                                });

                                                //テキストオブジェクト配置
                                                newText = new fabric.Textbox('状況入力', {
                                                    width: 150,
                                                    left: options.pointer.x - 20,
                                                    top: options.pointer.y - 10,
                                                    fill: '#000000',
                                                    fontFamily: 'Meiryo',
                                                    fontSize: 16,
                                                    textBackgroundColor: '#FFFFFF',
                                                    borderColor: 'red',
                                                    borderScaleFactor: 2,
                                                    opacity: 1
                                                });
                                            }
                                        });

                                        canvas.on('mouse:move', (options) => {
                                            if (!options.pointer)
                                                return;
                                            if (newEllipse && newEllipse.left && newEllipse.top) {
                                                newEllipse.width = Math.max(options.pointer.x - newEllipse.left, 0)
                                                newEllipse.height = Math.max(options.pointer.y - newEllipse.top, 0)
                                                canvas.requestRenderAll();
                                            }
                                        });

                                        canvas.on('mouse:up', (options) => {
                                            if (!options.pointer) {
                                                return;
                                            }
                                            if (newEllipse && newEllipse.width != 0 && newEllipse.height != 0) {
                                                canvas.add(newEllipse);
                                                cnt = cnt + 1;

                                                canvas.add(newText);
                                                cnt = cnt + 1;
                                            }

                                            // 初期化
                                            newEllipse = undefined;
                                            newText = undefined;

                                        });

                                        // 特定要素の削除
                                        document.addEventListener("keyup", function (e) {
                                            if (e.keyCode === 46) { //| e.keyCode === 8) { // delete と backspaceに対応
                                                canvas.remove(canvas.getActiveObject());
                                            }
                                        });

                                        canvas.on('mouse:dblclick', (options) => {
                                            canvas.renderAll();
                                        });

                                        // 保存ボタン押下イベント
                                        function OnButtonClick(canvasData) {

                                            // Canvasのデータを取得
                                            const canvas = document.getElementById('canvas');
                                            const encodedData = canvas.toDataURL("image/jpeg");

                                            // Buffer
                                            const fileData = encodedData.replace(/^data:\w+\/\w+;base64,/, '');
                                            //const decodedFile = new Buffer(fileData, 'base64');
                                            // ファイルの拡張子(png)
                                            const fileExtension = encodedData.toString().slice(encodedData.indexOf('/') + 1, encodedData.indexOf(';'));
                                            // ContentType(image/png)
                                            const contentType = encodedData.toString().slice(encodedData.indexOf(':') + 1, encodedData.indexOf(';'));

                                            var file_type = 'image/jpeg';
                                            var image_json = JSON.stringify(canvasData);

                                            // uniqueIdを設定する
//                                            var uniqueId = document.querySelector("#btn-send");
                                            var uniqueId = getQuery("id");
                                            var userId = getQuery("user");

                                            //システム日時よりファイル名を生成する
                                            var now = new Date();
                                            var year = now.getFullYear();
                                            var month = now.getMonth() + 1;
                                            var date = now.getDate();
                                            var hour = now.getHours();
                                            var min = now.getMinutes();
                                            var sec = now.getSeconds();
                                            var lifeImage = year.toString() + month.toString() + date.toString() + hour.toString() + min.toString() + sec.toString() + ".jpeg";
                                            var keyId = "";
                                            $.ajax({
                                                async: false,
                                                type: "POST",
                                                url: "./ajax/ajax_body_image.php",
                                                dataType: "text",
                                                data: {
                                                    "unique_id": uniqueId,
                                                    "user_id": userId,
                                                    "life_image": lifeImage,
                                                    "bin_file_data": encodedData,
                                                    "image_json": image_json
                                                }
                                            }).done(function (data) {
                                                console.log("処理スケジュールID : " + data);
                                                keyId = data;
                                            }).fail(function (jqXHR, textStatus, errorThrown) {
                                                console.log("ajax通信に失敗しました");
                                                console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                                                console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                                                console.log("errorThrown    : " + errorThrown.message); // 例外情報
                                                console.log("URL            : " + url);
                                            });

                                            //ドキュメント上の最初のフォームを取得
                                            const form = document.forms[0];

                                            //form要素にtype="hidden"のinputタグを挿入
                                            form.insertAdjacentHTML(
                                                    'beforeend',
                                                    '<input type="hidden" name="upAry[unique_id]" value="' + uniqueId + '" >'
                                                    );
                                            form.insertAdjacentHTML(
                                                    'beforeend',
                                                    '<input type="hidden" name="upAry[life_image]" value="' + lifeImage + '" >'
                                                    );
                                            form.insertAdjacentHTML(
                                                    'beforeend',
                                                    '<input type="hidden" name="upAry[bin_file_data]" value="' + encodedData + '" >'
                                                    );
                                            form.insertAdjacentHTML(
                                                    'beforeend',
                                                    '<input type="hidden" name="upAry[image_json]" value="' + image_json + '" >'
                                                    );

                                            window.location.href = '/report/kantaki/index.php?id=' + keyId + "&user=" + userId;
                                        }

                                        var supportsPassive = false;
                                        try {
                                            // getter として opts.passive を定義して、 addEventListener 内で呼ばれたことがわかるようにする
                                            var opts = Object.defineProperty({}, 'passive', {
                                                get: function () {
                                                    // 内部で opts.passive が呼ばれたら対応ブラウザ
                                                    // 用意しておいたフラグを有効にする
                                                    supportsPassive = true;
                                                }
                                            });
                                            // 試しに適当なイベントを補足し、 opts.passive が呼ばれるか試す
                                            window.addEventListener("test", null, opts);
                                        } catch (e) {
                                        }

                                        function addEventListenerWithOptions(target, type, handler, options) {
                                            var optionsOrCapture = options;
                                            if (!supportsPassive) {
                                                // 非対応ブラウザでは、他のオプションは全て捨て
                                                // { capture: bool } の値を useCapture の値として採用する
                                                optionsOrCapture = options.capture;
                                            }
                                            //
                                            target.addEventListener(type, handler, optionsOrCapture);
                                        }

                                        // URLから特定クエリを取得
                                        function getQuery(name) {
                                            url = window.location.href;
                                            name = name.replace(/[\[\]]/g, "\\$&");
                                            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                                                    results = regex.exec(url);
                                            if (!results) {
                                                return null;
                                            }
                                            if (!results[2]) {
                                                return '';
                                            }
                                            return decodeURIComponent(results[2].replace(/\+/g, " "));
                                        }
        </script>
    </body>

</html>