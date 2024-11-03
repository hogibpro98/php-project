$(function () {
  /* モーダル開閉 */
  $('#report .calendar button.calendar_open').on('click', function () {
    /* モーダル開閉 */
    if (!$(this).hasClass('is-open')) {
      $(this).next('ul').show();
      $(this).addClass('is-open');
    } else {
      $(this).next('ul').hide();
      $(this).removeClass('is-open');
    }
  });
  /* マーク押下 */
  $('#report .calendar .sched_sign button').on('click', function () {
    /* オブジェクト定義 */
    const td = $(this).parents("td");
    const tgtVal = $(this).val();
    const tgtInput = $(td).find("input[name$='[event_kb]']");
    /* マーク描画 */
    $(td).removeClass();
    const tdClass = "sign-" + tgtVal;
    $(td).addClass(tdClass);
    /* inputにデータを保持 */
    $(tgtInput).val(tgtVal);
    /* モーダルを閉じる */
    $(td).find('.calendar_open').removeClass('is-open');
    $(this).parents('.sched_sign').hide();
  });
  $('#report .calendar button.calendar_open').dblclick(function () {
    /* オブジェクト定義 */
    const td = $(this).parents("td");
    $(td).removeClass();
    const tdClass = "sign-";
    $(td).addClass(tdClass);
  });
});
