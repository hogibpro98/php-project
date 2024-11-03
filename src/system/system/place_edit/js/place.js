$(document).ready(function () {
  // プルダウンのoption内容をコピー
  const pd2 = $("#municipal option").clone();

  // 1→2連動
  $('#prefecture').change(function () {
    // lv1のvalue取得
    const lv1Val = $("#prefecture").val();

    // municipalのdisabled解除
    $('#municipal').removeAttr('disabled');

    // 一旦、municipalのoptionを削除
    $('#municipal option').remove();

    // (コピーしていた)元のmunicipalを表示
    $(pd2).appendTo('#municipal');

    // 選択値以外のクラスのoptionを削除
    if (lv1Val) {
      $('#municipal option[class != ' + lv1Val + ']').remove();
    }

    // 「▼選択」optionを先頭に表示
    $('#municipal').prepend(
      '<option value="" selected="selected">▼選択</option>'
    );
  });
});
