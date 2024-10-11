$(function () {
    // ドラッグしている要素を格納する変数
    let drag_item;

    // ドラッグが開始された時
    document.addEventListener('dragstart', (event) => {
        // ドラッグした要素を変数に格納
        drag_item = event.target;
        event.target.style.opacity = 0.6;
    });

    // ドラッグ中
    document.addEventListener('drag', () => {

    });

    // ドロップ可能エリアに入った時
    document.addEventListener('dragenter', (event) => {

        if (event.target.className == "skeduler-cell") {
            event.target.style.background = '#a9a9a9';
        }
    });

    // ドロップ可能エリア内にある時
    document.addEventListener("dragover", (event) => {
        event.preventDefault();
    }, false);

    // ドロップ可能エリアから離れた時
    document.addEventListener('dragleave', (event) => {
        // alert("ドロップ可能エリアから離れた時");
        if (event.target.className == "skeduler-cell") {
            event.target.style.background = '';
        }
    });
    //  ダブルクリック時の動作
    document.addEventListener('dblclick', function (event) {
        var scheduleType = event.target.getAttribute('data-schedule-type');
        var element      = "";
        var tgUrl        = "";
        var dlgName      = "";
        var scheduleId   = "";
        if(scheduleType === null){
            element = event.target.closest('#item');
            if(!element){
                return;
            }
            scheduleType = event.target.closest('#item').getAttribute('data-schedule-type');
            if(scheduleType){
               tgUrl      = element.getAttribute('data-url');
               dlgName    = element.getAttribute('data-dialog_name');
               scheduleId = element.getAttribute('data-schedule-id');
           }else{
               return;
           }
        }else{
            tgUrl      = event.target.getAttribute('data-url');
            dlgName    = event.target.getAttribute('data-dialog_name');
            scheduleId = event.target.getAttribute('data-schedule-id');
        }
        console.log(scheduleType);
        // スタッフスケジュール
        if (scheduleType === 'staff') {
            tgUrl    = tgUrl + '&schedule=' + scheduleId;
            $(".modal_setting").children().remove();

            let xhr  = new XMLHttpRequest();
            xhr.open('GET', tgUrl, true);
            xhr.addEventListener('load', function () {
                console.log(this.response);
                $(".modal_setting").append(this.response);
                $("." + dlgName).css("display", "block");
            });
            xhr.send();

        // 週間スケジュール
        } else if (scheduleType === 'week') {
            $(".modal_setting").children().remove();

            let xhr = new XMLHttpRequest();
            xhr.open('GET', tgUrl, true);
            xhr.addEventListener('load', function () {
                $(".modal_setting").append(this.response);
                $("." + dlgName).css("display", "block");
            });
            xhr.send();
        }
    });

    // ドラッグが終了した時
    document.addEventListener('dragend', () => {
            var startTime  = event.target.getAttribute('data-start-time');
            var startSplit = startTime.split(':');
            var startScale = startSplit[0] + ":00";
            var hash       = location.hash;
            var url        = location.href;
            if(hash){
                url        = url.replace(hash, "");
            }
             window.location.reload(true);
    });

    // ドロップ時の処理
    document.addEventListener("drop", (event) => {
        if (event.target.className == "skeduler-cell") {
            event.target.style.background = '';

            if (drag_item.className == 'add_menu') {
                var startTime   = event.target.getAttribute('data-start-time');
                var rootName    = event.target.getAttribute('data-root-name');
                var rootId      = event.target.getAttribute('data-root-id');
                var placeId     = event.target.getAttribute('data-place-id');
                var scheduleId  = drag_item.getAttribute('data-schedule-id');
                var endTime     = "";
                var startSplit  = startTime.split(':');
                var startCnvMin = parseInt(startSplit[0]) * 60 + parseInt(startSplit[1]);
                var strInterval = drag_item.getAttribute('data-interval');
                var interval    = !strInterval ? 60 : parseInt(strInterval);
                var endCnvMin   = startCnvMin + interval;
                var hour        = parseInt(endCnvMin / 60);
                var min         = parseInt(endCnvMin % 60);
                endTime         = hour.toString().padStart(2, '0') + ":" + min.toString().padStart(2, '0');
                var intHeight   = interval / 5 * 30;
                var height      = intHeight.toString();

                // パラメタ設定
                var outText     = "";
                var stfPlanId   = "";
                var week        = document.getElementById('selectWeek').value;
                var work        = drag_item.text;
                var status      = "未実施";
                var rootName    = event.target.getAttribute('data-root-name');
                var background  = drag_item.getAttribute('data-background');
                var borderColor = drag_item.getAttribute('data-border-color');

                $.ajax({
                    async    : false,
                    type     : "POST",
                    url      : "./ajax/staff_schedule.php",
                    dataType : "text",
                    data: {
                        "id"         : stfPlanId,
                        "week"       : week,
                        "start_time" : startTime,
                        "end_time"   : endTime,
                        "work"       : work,
                        "status"     : status,
                        "root_name"  : rootName,
                        "root_id"    : rootId,
                        "place_id"   : placeId
                    }
                }).done(function (data) {
                    // html上のdata属性にunique_idを設定
                    stfPlanId = data;
                    if (data) {
                        stfPlanId = data;
                        outText += '';
                        outText += '<div id="item" class="data data-grn" draggable="true" style="height: ' + height + 'px; max-width: 186px; opacity: 0.6;background:' + background + '; border-color:' + borderColor + ';" data-schedule-type="staff" data-schedule-id="' + data + '" data-root-name="' + rootName + '"' + '" data-root-id="' + rootId + '" data-place-id="' + placeId + '" data-start-time="' + startTime + '" data-end-time="' + endTime + '" title="' + startTime + ' - ' + endTime + '" data-url="/schedule/route_edit/dialog/staff_edit_dialog.php?id=' + stfPlanId + '" data-dialog_name="modal">';
                        outText += '  <div>';
                        outText += '    <span id="start_time" class="d_time">' + startTime + '</span><span class="d_time">~</span><span id="end_time" class="d_time">' + endTime + '</span>';
                        outText += '  </div>';
                        outText += '  <div>';
                        outText += '    <span id="work" class="dty_dets">' + drag_item.text + '</span>';
                        outText += '  </div>';
                        outText += '</div>';
                        // 文字列をhtmlノードに変換
                        var outNode = $.parseHTML(outText);
                        // 従業員スケジュールの差込
                        event.target.appendChild(outNode[0]);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // alert("ajaxエラー発生");
                    console.log("ajax通信に失敗しました");
                    console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                    console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                    console.log("errorThrown    : " + errorThrown.message); // 例外情報
                    console.log("URL            : " + url);
                });
            } else {
                var scheduleType = drag_item.getAttribute('data-schedule-type');

                drag_item.parentNode.removeChild(drag_item);
                var rootName     = event.target.getAttribute('data-root-name');
                var rootId       = event.target.getAttribute('data-root-id');
                var placeId      = event.target.getAttribute('data-place-id');
                // 移動先の開始時間を取得する
                var startTime    = event.target.getAttribute('data-start-time');
                var startSplit   = startTime.split(':');
                var startCnvMin  = parseInt(startSplit[0]) * 60 + parseInt(startSplit[1]);
                // オブジェクトの時間を取得する
                var dstartTime   = drag_item.getAttribute('data-start-time');
                var dendTime     = drag_item.getAttribute('data-end-time');
                // オブジェクトの時間幅を計算する
                var dstartSplit  = dstartTime.split(':');
                var dstartCnvMin = parseInt(dstartSplit[0]) * 60 + parseInt(dstartSplit[1]);
                var dendSplit    = dendTime.split(':');
                var dendCnvMin   = parseInt(dendSplit[0]) * 60 + parseInt(dendSplit[1]);
                // 差分を求め新しい終了時間を算出する
                var diffMin      = startCnvMin + dendCnvMin - dstartCnvMin;
                // 移動後の終了時間(文字列)を生成
                var hour         = parseInt(diffMin / 60);
                var min          = parseInt(diffMin % 60);
                var newEndTime   = hour.toString().padStart(2, '0') + ":" + min.toString().padStart(2, '0');

                // data属性値を変更する
                drag_item.dataset.rootName  = rootName;
                drag_item.dataset.startTime = startTime;
                drag_item.dataset.endTime   = newEndTime;

                // ドラッグオブジェクトを移動させる
                event.target.appendChild(drag_item);

                // 時刻データの書き換え
                drag_item.querySelector('#start_time').innerHTML = startTime;
                drag_item.querySelector('#end_time').innerHTML   = newEndTime;

                // パラメタ設定
                var id     = drag_item.getAttribute('data-schedule-id');
                var week   = document.getElementById('selectWeek').value;
                var status = "";

                if (scheduleType === "staff") {
                    // 従業員スケジュール更新処理
                    $.ajax({
                        async    : false,
                        type     : "POST",
                        url      : "./ajax/staff_schedule.php",
                        dataType : "text",
                        data: {
                            "id"         : id,
                            "week"       : week,
                            "start_time" : startTime,
                            "end_time"   : newEndTime,
                            "status"     : status,
                            "root_name"  : rootName,
                            "root_id"    : rootId,
                            "place_id"   : placeId
                        }
                    }).done(function (data) {
                        console.log("処理スケジュールID : " + data);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log("ajax通信に失敗しました");
                        console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                        console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                        console.log("errorThrown    : " + errorThrown.message); // 例外情報
                        console.log("URL            : " + url);
                    });
                } else if (scheduleType === "week") {
                    // 利用者スケジュール更新処理
                    $.ajax({
                        async    : false,
                        type     : "POST",
                        url      : "./ajax/week_schedule.php",
                        dataType : "text",
                        data: {
                            "schedule_id" : id,
                            "start_time"  : startTime,
                            "end_time"    : newEndTime,
                            "root_name"   : rootName
                        }
                    }).done(function (data) {
                        console.log("処理スケジュールID : " + data);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log("ajax通信に失敗しました");
                        console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                        console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                        console.log("errorThrown    : " + errorThrown.message); // 例外情報
                        console.log("URL            : " + url);
                    });
                }
            }
        }
        // 格納している変数を初期化
        drag_item = null;
        window.location.reload(true);
});
}());
$(document).ready(function () {
    // 曜日変更
    $(".month_select").change(function () {
        var week = $("#selectWeek").val();
        window.location.href = '/schedule/route_edit/index.php?week=' + week;
    });

    // ルート追加ダイアログ起動
    $("#btn_add_root").click(function () {
        $(".add_root").show();
    });
});
