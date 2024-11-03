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
  document.addEventListener('drag', () => {});

  // ドロップ可能エリアに入った時
  document.addEventListener('dragenter', (event) => {
    if (event.target.className == 'skeduler-cell') {
      event.target.style.background = '#a9a9a9';
    }
  });

  // ドロップ可能エリア内にある時
  document.addEventListener(
    'dragover',
    (event) => {
      event.preventDefault();
    },
    false
  );

  // ドロップ可能エリアから離れた時
  document.addEventListener('dragleave', (event) => {
    // alert("ドロップ可能エリアから離れた時");
    if (event.target.className == 'skeduler-cell') {
      event.target.style.background = '';
    }
  });
  //  ダブルクリック時の動作
  document.addEventListener('dblclick', function (event) {
    let scheduleType = event.target.getAttribute('data-schedule-type');
    let element = '';
    let tgUrl = '';
    let dlgName = '';
    if (scheduleType === null) {
      element = event.target.closest('#item');
      if (element != null) {
        const item = event.target.closest("#item");
        scheduleType = item.getAttribute('data-schedule-type');
        if (scheduleType) {
          tgUrl = element.getAttribute('data-url');
          dlgName = element.getAttribute('data-dialog_name');
        } else {
          return;
        }
      }
    } else {
      tgUrl = event.target.getAttribute('data-url');
      dlgName = event.target.getAttribute('data-dialog_name');
    }
    // スケジュール詳細
    if (scheduleType === 'service') {
      const modalNode = document.getElementsByClassName("modal_setting");
      const node = modalNode.lastElementChild;
      if (node != undefined) {
        node.lastElementChild.remove();
      }

      const xhr = new XMLHttpRequest();
      xhr.open('GET', tgUrl, true);
      xhr.addEventListener('load', function () {
        console.log(this.response);
        $('.modal_setting').append(this.response);
        $('.' + dlgName).css('display', 'block');
      });
      xhr.send();

      // 週間スケジュール
    } else if (scheduleType === 'week') {
      const modalNode = document.getElementsByClassName("modal_setting");
      const node = modalNode.lastElementChild;
      if (node != undefined) {
        node.lastElementChild.remove();
      }

      const xhr = new XMLHttpRequest();
      xhr.open('GET', tgUrl, true);
      xhr.addEventListener('load', function () {
        $('.modal_setting').append(this.response);
        $('.' + dlgName).css('display', 'block');
      });
      xhr.send();
    }
  });

  // ドラッグが終了した時
  document.addEventListener('dragend', () => {
    // event.target.style.opacity = 1;
    const startTime = event.target.getAttribute("data-start-time");
    const startSplit = startTime.split(":");
    const startScale = startSplit[0] + ":00";
    const hash = location.hash;
    let url = location.href;
    if (hash) {
      url = url.replace(hash, '');
    }
    //         window.location.href = url + "#" + startScale;
    window.location.reload(true);
  });

  // ドロップ時の処理
  document.addEventListener('drop', (event) => {
    if (event.target.className == 'skeduler-cell') {
      event.target.style.background = '';

      const scheduleType = drag_item.getAttribute("data-schedule-type");

      drag_item.parentNode.removeChild(drag_item);
      const week = event.target.getAttribute("data-root-name");
      // 移動先の開始時間を取得する
      const startTime = event.target.getAttribute("data-start-time");
      const startSplit = startTime.split(":");
      const startCnvMin =
        parseInt(startSplit[0]) * 60 + parseInt(startSplit[1]);
      // オブジェクトの時間を取得する
      const dstartTime = drag_item.getAttribute("data-start-time");
      const dendTime = drag_item.getAttribute("data-end-time");
      // オブジェクトの時間幅を計算する
      const dstartSplit = dstartTime.split(":");
      const dstartCnvMin =
        parseInt(dstartSplit[0]) * 60 + parseInt(dstartSplit[1]);
      const dendSplit = dendTime.split(":");
      const dendCnvMin = parseInt(dendSplit[0]) * 60 + parseInt(dendSplit[1]);
      // 差分を求め新しい終了時間を算出する
      const diffMin = startCnvMin + dendCnvMin - dstartCnvMin;
      // 移動後の終了時間(文字列)を生成
      const hour = parseInt(diffMin / 60);
      const min = parseInt(diffMin % 60);
      const newEndTime =
        hour.toString().padStart(2, "0") +
        ":" +
        min.toString().padStart(2, "0");
      // 日跨ぎ判定
      const hmtgFlg = drag_item.getAttribute("data-hmtg-flg");
      if (!hmtgFlg) {
        // data属性値を変更する
        drag_item.dataset.rootName = week;
        drag_item.dataset.startTime = startTime;
        drag_item.dataset.endTime = newEndTime;

        // ドラッグオブジェクトを移動させる
        event.target.appendChild(drag_item);

        // 時刻データの書き換え
        drag_item.querySelector('#start_time').innerHTML = startTime;
        drag_item.querySelector('#end_time').innerHTML = newEndTime;

        if (scheduleType === 'service') {
          // パラメタ設定
          const serviceId = drag_item.getAttribute("data-service-id");

          // 利用者スケジュール更新処理
          $.ajax({
            async: false,
            type: 'POST',
            url: './ajax/service_schedule.php',
            dataType: 'text',
            data: {
              id: serviceId,
              start_time: startTime,
              end_time: newEndTime,
            },
          })
            .done(function (data) {
              console.log('処理スケジュールID : ' + data);
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
              console.log('ajax通信に失敗しました');
              console.log('jqXHR          : ' + jqXHR.status); // HTTPステータスが取得
              console.log('textStatus     : ' + textStatus); // タイムアウト、パースエラー
              console.log('errorThrown    : ' + errorThrown.message); // 例外情報
              console.log('URL            : ' + url);
            });
        } else if (scheduleType === 'week') {
          // パラメタ設定
          const scheduleId = drag_item.getAttribute("data-schedule-id");

          // 週間スケジュール更新処理
          $.ajax({
            async: false,
            type: 'POST',
            url: './ajax/week_schedule.php',
            dataType: 'text',
            data: {
              schedule_id: scheduleId,
              week: week,
              start_time: startTime,
              end_time: newEndTime,
            },
          })
            .done(function (data) {
              console.log('処理スケジュールID : ' + data);
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
              console.log('ajax通信に失敗しました');
              console.log('jqXHR          : ' + jqXHR.status); // HTTPステータスが取得
              console.log('textStatus     : ' + textStatus); // タイムアウト、パースエラー
              console.log('errorThrown    : ' + errorThrown.message); // 例外情報
              console.log('URL            : ' + url);
            });
        }
      } else {
        alert('日またぎのドラッグ操作は禁止されています');
        window.location.reload();
      }
    }
    // 格納している変数を初期化
    drag_item = null;
  });
});

// window.addEventListener('load', () => {
$(function () {
  $('.btn_prev_mon').click(function () {
    // 本日を作成.
    const PrevMonFrom = new Date();
    const PrevMonTo = new Date();

    // 日付に1を設定します.
    PrevMonFrom.setDate(1);

    // 1ヶ月加えて翌月
    PrevMonTo.setMonth(PrevMonTo.getMonth() + 1);
    // 日付に0を設定し、該当月の月末
    PrevMonTo.setDate(0);

    const year1 = PrevMonFrom.getFullYear();
    const month1 = PrevMonFrom.getMonth() + 1;
    const date1 = PrevMonFrom.getDate();

    const year2 = PrevMonTo.getFullYear();
    const month2 = PrevMonTo.getMonth() + 1;
    const date2 = PrevMonTo.getDate();

    const fromDate =
      ("0000" + year1).slice(-4) +
      "-" +
      ("00" + month1).slice(-2) +
      "-" +
      ("00" + date1).slice(-2);
    const toDate =
      ("0000" + year2).slice(-4) +
      "-" +
      ("00" + month2).slice(-2) +
      "-" +
      ("00" + date2).slice(-2);
    $('.month_from').val(fromDate);
    $('.month_to').val(toDate);
  });
  $('.btn_next_mon').click(function () {
    // 本日を作成.
    const PrevMonFrom = new Date();
    const PrevMonTo = new Date();
    // 1ヶ月加えて翌月
    PrevMonFrom.setMonth(PrevMonFrom.getMonth() + 1);
    // 日付に1を設定します.
    PrevMonFrom.setDate(1);

    // 1ヶ月加えて翌月
    PrevMonTo.setMonth(PrevMonTo.getMonth() + 2);
    // 日付に0を設定し、該当月の月末
    PrevMonTo.setDate(0);

    const year1 = PrevMonFrom.getFullYear();
    const month1 = PrevMonFrom.getMonth() + 1;
    const date1 = PrevMonFrom.getDate();

    const year2 = PrevMonTo.getFullYear();
    const month2 = PrevMonTo.getMonth() + 1;
    const date2 = PrevMonTo.getDate();

    const fromDate =
      ("0000" + year1).slice(-4) +
      "-" +
      ("00" + month1).slice(-2) +
      "-" +
      ("00" + date1).slice(-2);
    const toDate =
      ("0000" + year2).slice(-4) +
      "-" +
      ("00" + month2).slice(-2) +
      "-" +
      ("00" + date2).slice(-2);
    $('.month_from').val(fromDate);
    $('.month_to').val(toDate);
  });
  $('#duplicate').click(function () {
    console.log('この週間スケジュールを別の利用者に複製クリック');
    const userId = $(".tgt-usr_id").val();
    if (userId == null || userId == '') {
      alert('利用者を指定していません。');
    } else {
      $('.dupli_modal').show();
    }
  });
});
