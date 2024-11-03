$(function () {
  // 介護保険証モーダル
  $('.office_simple').on('click', function () {
    // 各種データ取得
    const ins1_id = $(this).data("ins1_id");
    const ins1_stn1 = $(this).data("ins1_start_nengo");
    const ins1_sty1 = $(this).data("ins1_start_year1");
    const ins1_stm1 = $(this).data("ins1_start_month1");
    const ins1_std1 = $(this).data("ins1_start_dt1");
    const ins1_edn1 = $(this).data("ins1_end_nengo");
    const ins1_edy1 = $(this).data("ins1_end_year1");
    const ins1_edm1 = $(this).data("ins1_end_month1");
    const ins1_edd1 = $(this).data("ins1_end_dt1");
    const ins1_stn2 = $(this).data("ins1_start_nengo2");
    const ins1_sty2 = $(this).data("ins1_start_year2");
    const ins1_stm2 = $(this).data("ins1_start_month2");
    const ins1_std2 = $(this).data("ins1_start_dt2");
    const ins1_edn2 = $(this).data("ins1_end_nengo2");
    const ins1_edy2 = $(this).data("ins1_end_year2");
    const ins1_edm2 = $(this).data("ins1_end_month2");
    const ins1_edd2 = $(this).data("ins1_end_dt2");
    const ins1_insno = $(this).data("ins1_insure_no");
    const ins1_indno = $(this).data("ins1_insured_no");
    const ins1_rank = $(this).data("ins1_care_rank");

    // 要素書き換え
    $('.ins1_id').val(ins1_id);
    $('.ins1_start_nengo').val(ins1_stn1);
    $('.ins1_start_year1').val(ins1_sty1);
    $('.ins1_start_month1').val(ins1_stm1);
    $('.ins1_start_dt1').val(ins1_std1);
    $('.ins1_end_nengo').val(ins1_edn1);
    $('.ins1_end_year1').val(ins1_edy1);
    $('.ins1_end_month1').val(ins1_edm1);
    $('.ins1_end_dt1').val(ins1_edd1);
    $('.ins1_start_nengo2').val(ins1_stn2);
    $('.ins1_start_year2').val(ins1_sty2);
    $('.ins1_start_month2').val(ins1_stm2);
    $('.ins1_start_dt2').val(ins1_std2);
    $('.ins1_end_nengo2').val(ins1_edn2);
    $('.ins1_end_year2').val(ins1_edy2);
    $('.ins1_end_month2').val(ins1_edm2);
    $('.ins1_end_dt2').val(ins1_edd2);
    $('.ins1_insure_no').val(ins1_insno);
    $('.ins1_insured_no').val(ins1_indno);
    $('.ins1_care_rank').val(ins1_rank);

    // オープン
    $('.cont_office_simple').show();
  });
  $('.office_simple .close').on('click', function () {
    $('.cont_office_simple').hide();
  });

  // 和暦を入力したら西暦を設定する
  $('#era_list').on('focusout', function () {
    convSeireki();
  });

  // 和暦を入力したら西暦を設定する
  $('#era_yr').on('focusout', function () {
    convSeireki();
  });

  // 西暦(年)を入力したら和暦を設定する
  $('#birth_yr').on('focusout', function () {
    convWareki();
  });
  // 西暦（月）を入力したら和暦を設定する
  $('#birth_m').on('focusout', function () {
    convWareki();
  });

  // 西暦（日）を入力したら和暦を設定する
  $('#birth_d').on('focusout', function () {
    convWareki();
  });

  // 拠点変更
  $('.sel_place').on('change', function () {
    changePlace($(this));
  });
});
function convSeireki() {
  const wareki = $("#era_list").val();
  var year = $('#era_yr').val();
  let result = 0;

  //    if (wareki != null && year != null) {
  if (wareki && year) {
    // 明治から西暦に変換するには「1867を足す」
    // 大正から西暦に変換するには「1911を足す」
    // 昭和から西暦に変換するには「1925を足す」
    // 平成から西暦に変換するには「1988を足す」
    // 令和から西暦に変換するには「2018を足す」
    if (wareki === '明治') {
      result = Number(year) + 1876;
    } else if (wareki === '大正') {
      result = Number(year) + 1911;
    } else if (wareki === '昭和') {
      result = Number(year) + 1925;
    } else if (wareki === '平成') {
      result = Number(year) + 1988;
    } else if (wareki === '令和') {
      result = Number(year) + 2018;
    } else {
      result = null;
    }

    if (result) {
      $('#birth_yr').val(result);
    } else {
      $('#birth_yr').val('');
    }

    var year = $('#birth_yr').val();
    const month = $("#birth_m").val();
    const day = $("#birth_d").val();
    if (year && month && day) {
      const age = calcAge(year, month, day);
      $('#birth_age').val(age);
    }
  }
}

function convWareki() {
  console.log('convWareki');
  const year = $("#birth_yr").val();
  const month = $("#birth_m").val();
  const day = $("#birth_d").val();
  if (year && month && day) {
    const birthday = new Date(year, month, day);
    const wareki = convert_to_japanese_calendar(birthday);
    if (wareki) {
      $('#era_list').val(wareki[0]);
      $('#era_yr').val(wareki[1]);
    } else {
      $('#era_list').val('');
      $('#era_yr').val('');
    }
    const target = new Date();
    const age = calcAge(year, month, day);
    $('#birth_age').val(age);
  }
}

/**
 * 指定した西暦の年月日を和暦に変換する
 * @param {date} target - 変換する年月日
 */
function convert_to_japanese_calendar(target) {
  // 元号の情報
  const jaCalender = [
    {
      era: "明治",
      start: "1868/1/25",
    },
    {
      era: "大正",
      start: "1912/7/30",
    },
    {
      era: "昭和",
      start: "1926/12/25",
    },
    {
      era: "平成",
      start: "1989/1/8",
    },
    {
      era: "令和",
      start: "2019/5/1",
    },
  ];
  const result = [];
  for (let i = jaCalender.length - 1; i >= 0; i--) {
    const t = new Date(jaCalender[i].start);
    // 元号の範囲に入っている場合
    if (target >= t) {
      // 和暦に変換して返す
      result.push(jaCalender[i].era);
      result.push(target.getFullYear() - t.getFullYear() + 1);
      result.push(
        jaCalender[i].era +
          (target.getFullYear() - t.getFullYear() + 1) +
          '年' +
          (target.getMonth() + 1) +
          '月' +
          target.getDate() +
          '日'
      );
      return result;
    }
    // 設定した元号の範囲に入らなかった場合
    if (i <= 0) {
      return null;
    }
  }
}

// 満年齢計算
function calcAge(y, m, d) {
  const birthdate =
    parseInt(y, 10) * 10000 + parseInt(m, 10) * 100 + parseInt(d, 10);
  const today = new Date();
  const targetdate =
    today.getFullYear() * 10000 +
    (today.getMonth() + 1) * 100 +
    today.getDate();
  const age = Math.floor((targetdate - birthdate) / 10000);
  return age;
}

// 拠点変更
function changePlace(element) {
  $(element)
    .parent()
    .parent()
    .parent()
    .find('.cngPlace')
    .each(function () {
      $(this).hide();
    });

  const selPlaceId = $(element).val();
  $(element)
    .parent()
    .parent()
    .parent()
    .find('.cngPlace')
    .each(function () {
      const placeId = $(this).data("place_id");
      if (!selPlaceId || selPlaceId === placeId) {
        $(this).show();
      } else {
        $(this).removeAttr('selected');
        $(this).val('');
      }
    });
}
