// USER LIST - NG JS //////////////////////////////////////////////////////////////////
function showNgPop(msg) {
  $('.ng').click(function () {
    $('.no_data-in').empty();
    $('.no_data-in').append(msg);

    if (window.innerWidth > 700) {
      const leftPosition = $(this).offset().left;
      const topPosition = $(this).offset().top - 340;
      $('.no_data-in').css({ left: leftPosition, top: topPosition }).show();
    } else {
      const mtopPosition = $(this).offset().top - 395;
      $('.no_data-in').css({ left: '80px', top: mtopPosition }).show();
    }
  });
}

$(function () {
  // 当月を表示
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
  // 翌月を表示
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
});
