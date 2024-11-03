//
// // =======================================================================
// 入力フィールドの値を取得（複数）
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function getVal(tgtClass) {
  const res = {};

  $('.' + tgtClass).each(function () {
    const type = $(this).attr("type");
    switch (type) {
      case 'checkbox':
      case 'radio':
        if (!res[$(this).attr('name')]) {
          res[$(this).attr('name')] = [];
        }
        if ($(this).prop('checked')) {
          res[$(this).attr('name')].push($(this).val());
        }
        break;

      default:
        res[$(this).attr('name')] = $(this).val();
        break;
    }
  });

  return res;
}
// =======================================================================
// 入力フィールドに値を反映（複数）
// =======================================================================
/* [引数]
 *     ① 連想配列
 *
 * [戻り値]
 *     無し
 *
 * -----------------------------------------------------------------------
 */
function setMultiValue(tgtAry) {
  for (const key in tgtAry) {
    setValue(key, tgtAry[key]);
  }
}

// =======================================================================
// 入力フィールドに値を反映
// =======================================================================
/* [引数]
 *     ① 対象フィールド名称
 *     ② 設定値
 *
 * [戻り値]
 *     無し
 *
 * -----------------------------------------------------------------------
 */
function setValue(targetName, data) {
  // input要素を書き換え
  var tgtDom = $('input[name="' + targetName + '"]');
  if (tgtDom.length) {
    // type 判定
    if (tgtDom.attr("type") === "checkbox") {
      tgtDom.each(function () {
        if (String(data).indexOf($(this).val()) != -1) {
          $(this).prop("checked", true).change();
        } else {
          $(this).prop("checked", false);
        }
      });
    } else {
      tgtDom.val(data).change();
    }
  }

  // selectboxを書き換え
  var tgtDom = $('select[name="' + targetName + '"]');
  if (tgtDom.length) {
    tgtDom.find("option").each(function () {
      if ($(this).val() === data) {
        $(this).prop("selected", true).change();
      }
      if ($(this).val() !== data) {
        $(this).prop("selected", false).change();
      }
    });
  }

  // textareaを書き換え
  var tgtDom = $('textarea[name="' + targetName + '"]');
  if (tgtDom.length) {
    tgtDom.val(data).change();
  }
}

// =======================================================================
// ボタン押下時のイベント設定
// =======================================================================
/* [引数]
 *     無し
 *
 * [戻り値]
 *     無し
 *
 * -----------------------------------------------------------------------
 */
function ctrlBtnClick(btnType) {
  switch (btnType) {
    case '行追加':
      addRecord();
      break;

    case '行削除':
      delDomClass();
      break;

    default:
      break;
  }
}

// =======================================================================
// 行追加関数
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function addRecord(
  templateId = "template",
  changeStrAry = {},
  tgtDomId = "f-detailBody"
) {
  const originHtml = $('#' + templateId).html();
  const nowSeq = $('#' + templateId).data('seq');
  const nextSeq = nowSeq + 1;
  if (Object.keys(changeStrAry).length === 0) {
    changeStrAry = $("#" + templateId).data("changestrary");
  }

  // seq 反映
  changeStrAry = changeStr(JSON.stringify(changeStrAry), "seq", nextSeq);
  $("#" + templateId).data("seq", nextSeq);

  const tempAry = JSON.parse(changeStrAry);

  console.log(tempAry);
  let tgtHtml = changeAry(originHtml, tempAry);

  tgtHtml = changeStr(tgtHtml, '=""', "");
  tgtHtml = changeStr(tgtHtml, "seq", nextSeq);

  const obj = $(tgtHtml).appendTo($('#' + tgtDomId));

  // 行数設定
  setOrder("orderNo");
  setOrder("orderNo1");
  setOrder("orderNo2");
  setOrder("orderNo3");
  setOrder("orderRowNo");
  setOrder("orderRowNo1");
  setOrder("orderRowNo2");
  setOrder("orderRowNo3");

  // selectbox対応
  obj.find("select").each(function () {
    const defaultVal = $(this).data('defaultval');
    $(this)
      .find("option")
      .each(function () {
        if ($(this).val() == defaultVal) {
          $(this).prop("selected", true);
        }
        if ($(this).val() != defaultVal) {
          $(this).prop("selected", false);
        }
      });
  });
}

// =======================================================================
// 文字列置換関数(wrap)
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function changeAry(tgtStr, changeAry) {
  let res = '';
  for (const key in changeAry) {
    if (res) {
      res = changeStr(res, key, changeAry[key]);
    } else {
      res = changeStr(tgtStr, key, changeAry[key]);
    }
  }

  return res;
}

// =======================================================================
// 文字列置換関数
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function changeStr(tgtStr, beforeStr, afterStr) {
  let res = '';
  res = tgtStr.replaceAll(beforeStr, afterStr);
  return res;
}

// =======================================================================
// 行追加関数
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function copyRecord(
  templateId = "template",
  changeStrAry = {},
  tgtDomObj = "f-detailBody"
) {
  const originHtml = $('#' + templateId).html();
  const nowSeq = $('#' + templateId).data('seq');
  const nextSeq = nowSeq + 1;
  if (Object.keys(changeStrAry).length === 0) {
    changeStrAry = $("#" + templateId).data("changestrary");
  }

  // seq 反映
  changeStrAry = changeStr(JSON.stringify(changeStrAry), "seq", nextSeq);
  $("#" + templateId).data("seq", nextSeq);

  const tempAry = JSON.parse(changeStrAry);

  let tgtHtml = changeAry(originHtml, tempAry);
  tgtHtml = changeStr(tgtHtml, '=""', "");
  tgtHtml = changeStr(tgtHtml, "seq", nextSeq);

  const obj = $(tgtHtml).insertAfter($(tgtDomObj).closest('tr'));

  // selectbox対応
  obj.find("select").each(function () {
    const defaultVal = $(this).data('defaultval');
    $(this)
      .find("option")
      .each(function () {
        if ($(this).val() == defaultVal) {
          $(this).prop("selected", true);
        }
        if ($(this).val() != defaultVal) {
          $(this).prop("selected", false);
        }
      });
  });

  // input要素対応
  obj.find("input").each(function (index) {
    const defaultVal = $(tgtDomObj).closest('tr').find('input').eq(index).val();
    $(this).val(defaultVal);
  });

  // textarea要素対応
  obj.find("textarea").each(function (index) {
    const defaultVal = $(tgtDomObj)
      .closest('tr')
      .find('textarea')
      .eq(index)
      .val();
    $(this).val(defaultVal);
  });

  // 行数設定
  setOrder("orderNo");
  setOrder("orderNo1");
  setOrder("orderNo2");
  setOrder("orderNo3");
  setOrder("orderRowNo");
  setOrder("orderRowNo1");
  setOrder("orderRowNo2");
  setOrder("orderRowNo3");
}

// =======================================================================
// 行削除関数
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function delDomClass(tgtDelClass = "f-del-check") {
  $("tr." + tgtDelClass).remove();

  // 行数設定
  setOrder("orderNo");
  setOrder("orderNo1");
  setOrder("orderNo2");
  setOrder("orderNo3");
  setOrder("orderRowNo");
  setOrder("orderRowNo1");
  setOrder("orderRowNo2");
  setOrder("orderRowNo3");
}

// =======================================================================
// 行数設定関数
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function setOrder(tgtClass) {
  let orderNo = 1;
  $("." + tgtClass).each(function () {
    if ($(this).attr("type") === "hidden") {
      $(this).val(orderNo);
    } else {
      $(this).html(orderNo);
    }
    orderNo = orderNo + 1;
  });
}

// =======================================================================
// 行追加関数
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function removeDom(tgtDomId) {
  $("#" + tgtDomId).html("");
}

// =======================================================================
// 入力フィールドの値を取得（複数）
// =======================================================================
/* [引数]
 *     ① 対象クラス
 *
 * [戻り値]
 *     連想配列 res['name'] = '入力値'
 *
 * -----------------------------------------------------------------------
 */
function noticeModal(msg) {
  // タイマーの戻り値保持用
  let timer_process;

  $('.system-modal-msg-M').html(msg);
  $('.member-modal').show();

  timer_process = setTimeout(function () {
    $('.member-modal').fadeOut();
  }, 2000);

  return false;
}

/* ローディング処理
----------------------------------------------------------------------------- */
// $(window).on('load',function () { //全ての読み込みが完了したら実行
//    $('.loading').fadeOut(200);
// });

/* ページ内リンクの処理  */
$(function () {
  // #で始まるアンカーをクリックした場合に処理
  $('a.pagelink').click(function () {
    // スクロールの速度
    const speed = 400; // ミリ秒
    // アンカーの値取得
    const href = $(this).attr("href");
    // 移動先を取得
    const target = $(href == "#" || href == "" ? "html" : href);
    // 移動先を数値で取得
    const position = target.offset().top;
    // スムーススクロール
    $('body,html').animate({ scrollTop: position }, speed, 'swing');
    return false;
  });
});

/* ヘッダー：SPナビ
----------------------------------------------------------------------------- */
$(function () {
  $('.header-sp button,  .header-nav-main ul li a').on('click', function () {
    // メニューボタン表示モード/非表示モード制御
    $('.header-sp button').toggleClass('is-open');

    // ハンバーガーメニュー表示/非表示制御
    if ($('.header-sp button').hasClass('is-open')) {
      $('.header-nav').addClass('is-open');
    } else {
      $('.header-nav').removeClass('is-open');
    }
  });
});

// セレクトボックスによるサブミット
$(function () {
  $('.nav-search').on('change', function () {
    $('.main').remove();
    $(this).after('<input type="hidden" name="btnSearch" value="true">');
    $(this).parents('form').find('input,select').prop('disabled', false);
    $(this).parents('form').submit();
  });
});

/* validator.engine：設定
----------------------------------------------------------------------------- */
$(function () {
  setValidate();
});

/* スライダー：slick
----------------------------------------------------------------------------- */
// $(function(){
//    $(".mv-slider").slick({
//        autoplay: true,
//	autoplaySpeed: 6000,
//        speed: 1000,
//	arrows: false,
//	infinite: true,
//	pauseOnHover: false,
//        slidesToShow: 1,
//        slidesToScroll: 3,
//    });
// });

/* スライダー：slick
----------------------------------------------------------------------------- */
// $(function(){
//    if($(".slider-normal").length){
//        $(".slider-normal ul").slick({
//            dots:true,
//            slidesToShow:3,
//            slidesToScroll:3,
//            autoplay:true,
//            responsive: [{
//                breakpoint: 769,
//                settings: {
//                    slidesToShow:1,
//                    slidesToScroll:1,
//                }
//            }]
//        });
//    };
// });

/* コンテンツスライダー
----------------------------------------------------------------------------- */
// $(function(){
//    $(".contents-slider").slick({
//        autoplay: true,
//        autoplaySpeed: 4000,
//        speed: 1500,
//        arrows: true,
//        infinite: true,
//        pauseOnHover: false,
//        slidesToShow: 5,
//        slidesToScroll: 1,
//        centerMargin:'20px',
//        prevArrow:'<button type="button" class="prev-arrow"><i class="fas fa-chevron-left"></i></button>',
//        nextArrow:'<button type="button" class="next-arrow"><i class="fas fa-chevron-right"></i></button>',
//        responsive: [{
//            breakpoint: 769,
//            settings: {
//                slidesToShow:3,
//                slidesToScroll:1
//            }
//        },{
//            breakpoint: 1025,
//            settings: {
//                slidesToShow:3,
//                slidesToScroll:1
//            }
//        }]
//    });
// });

/* 商品詳細
----------------------------------------------------------------------------- */
// $(function(){
// var slide_main = $(".shop-slide").slick({
//    asNavFor: ".shop-slide-navigation",
//    infinite: true,
//    slidesToShow: 1,
//    slidesToScroll: 1,
//    fade: true,
//    dots: false,
//  });
//  var slide_sub = $(".shop-slide-navigation").slick({
//    asNavFor: ".shop-slide",
//    centerMode: true,
//    infinite: true,
//    slidesToShow: 4,
//    slidesToScroll: 1,
//    autoplay: true,
//    autoplaySpeed: 4000,
//    speed: 400,
//    prevArrow:'<button type="button" class="prev-arrow"><i class="fas fa-chevron-left"></i></button>',
//    nextArrow:'<button type="button" class="next-arrow"><i class="fas fa-chevron-right"></i></button>',
//    focusOnSelect: true,
//    dots: true,
//        responsive: [{
//            breakpoint: 768,
//            settings: {
//                slidesToShow:2,
//                slidesToScroll:1
//            }
//        }]
//  });
//  var open_window_Width = $(window).width();
//  $(window).resize(function() {
//    var width = $(window).width();
//    if (open_window_Width != width) {
//      slide_main.slick("setPosition");
//      slide_sub.slick("setPosition");
//    }
//  });
// });

/* タブ切り替え
----------------------------------------------------------------------------- */

$(function () {
  if ($('.tab').length) {
    $('.tab-btn li,.tab-btn tr').on('click', function () {
      const parentTab = $(this).parents(".tab");
      const index = $(parentTab).find(".tab-btn li,.tab-btn tr").index(this);
      $(parentTab).find('.tab-main .tab-main-box').hide();
      $(parentTab).find('.tab-main .tab-main-box').eq(index).fadeIn();
      $('.tab-btn li').removeClass('is-current');
      $(this).addClass('is-current');
      $(parentTab)
        .find('.tab-main')
        .removeClass('is-show')
        .eq(index)
        .addClass('is-show');
    });
  }
});

// $(function() {
//    var current_element = document.getElementById("no-current");
//    // クラス名を削除
//    current_element.classList.remove("is-current");
// });

/* モーダルウィンドウ
----------------------------------------------------------------------------- */
$(function () {
  if ($('.modal').length) {
    /* モーダルを開く */
    $('.modal-open').on('click', function () {
      const id = $(this).attr("id");
      const tgt = "#tgt-" + id;
      $(tgt).fadeIn();
    });

    /* モーダルを閉じる */
    $('.modal-close').on('click', function () {
      $(this).parents('.modal').fadeOut();
    });
  }
});

/* 画像アップローダー
----------------------------------------------------------------------------- */
/*
 * ファイルアップロード時操作
 * upload          :1ファイルをアップロードするためのまとまり
 * upload-input-file      :ファイルをアップロードするinputタグ
 * upload-input-file-btn  :ファイル選択画面を開く為のボタン
 *                          (input[type=file]は表示文字編集不可の為hideする)
 * upload-input-file-clear:クリアボタンの押下情報を保持
 *                         サーバー登録済ファイルのコピー,削除に使用
 * upload-input-file-name :サーバー登録済のファイル名,選択されたファイル名を表示
 */

$(function () {
  if ($('.upload').length) {
    const defaultMsg = "選択されていません";

    // ボタン押下時、input[type=file] を開く
    // $('.upload-input-file-btn,.upload-preview').on('click',function(){
    $('.upload-input-file-btn').on('click', function () {
      $(this).parents('.upload').find('.upload-input-file').click();
    });

    // アップロード済みファイル名描画
    $('.upload-input-file-name').each(function () {
      const fileName = $(this).data("fname");
      if (fileName) {
        $(this).text(fileName);
      } else {
        $(this).text(defaultMsg);
      }
    });

    // プレビュー表示
    $('.upload-input-file').on('change', function (e) {
      const fileName = $(this).prop("files")[0].name;
      $(this).siblings('.upload-input-file-name').text(fileName);
      $(this).siblings('.upload-input-file-clear').val(false);

      const fileImg = e.target.files[0];
      const reader = new FileReader();
      const $preview = $(this).parents(".upload").find(".upload-preview");
      t = this;
      if (fileImg.type.indexOf('image') < 0) {
        return false;
      }
      reader.onload = (function (fileImg) {
        return function (e) {
          $preview.empty();
          $preview.append(
            $('<img>').attr({
              src: e.target.result,
              title: fileImg.name,
            })
          );
        };
      })(fileImg);
      reader.readAsDataURL(fileImg);
    });
    // ファイルをクリア
    $('.upload-clear').on('click', function () {
      $(this).siblings('.upload-input-file').val('');
      $(this).siblings('.upload-input-file-name').text(defaultMsg);
      $(this).siblings('.upload-input-file-clear').val(true);
      $(this).parents('.upload').find('.upload-preview').empty(); // ★追加（プレビュー画像のクリア）
    });
  }
});

// 複数アップロード
$(function () {
  $('input[type=file].img-multi').after('<div class="img-multi"></div>');

  // アップロードするファイルを複数選択
  $('input[type=file].img-multi').change(function () {
    $('.img-multi').html('');
    const file = $(this).prop("files");
    let img_count = 1;
    $(file).each(function (i) {
      if (!file[i].type.match('image.*')) {
        $(this).val('');
        $('.img-multi').html('');
        return;
      }
      const reader = new FileReader();
      reader.onload = function () {
        const img_src = $("<img>").attr("src", reader.result);
        $('.img-multi').append(img_src);
      };
      reader.readAsDataURL(file[i]);
      img_count = img_count + 1;
    });
  });
});

/* 検索、アコーディオン
----------------------------------------------------------------------------- */

$(function () {
  $('.box-search .search-add').on('click', function () {
    $(this).toggleClass('is-open');
    $(this).next('.search-detail').slideToggle().toggleClass('is-open');
  });
});

/* アコーディオンメニュー
----------------------------------------------------------------------------- */
$(function () {
  $('.accordion-btn,.accordion-btn-b').on('click', function () {
    const tgt = $(this).next(".accordion-tgt");

    if (tgt.hasClass('is-close')) {
      tgt.slideDown().removeClass('is-close');
      $(this).removeClass('is-close');
    } else {
      tgt.slideUp().addClass('is-close');
      $(this).addClass('is-close');
    }
  });
});

/* バリデートチェック
----------------------------------------------------------------------------- */
// 登録不要な入力時のチェック用(ログイン等)
$(function () {
  $('.p-form-validate-nocheck').each(function () {
    const id = $(this).attr("id");
    $('#' + id).validationEngine('attach', {
      onValidationComplete: function (form, status) {
        // バリデートチェック結果判定
        if (status === true) {
          // エラーなし: Submit実行
          return true;
        } else {
          $('.loading').fadeOut(200);
          // エラー発生: Submit中止
          return false;
        }
      },
    });
  });
});

// 登録前チェック用(Submit制御)
$(function () {
  let execFlag = false;

  $('.p-form-validate').each(function () {
    const id = $(this).attr("id");

    $('#' + id).validationEngine('attach', {
      promptPosition: 'bottomLeft',
      onValidationComplete: function (form, status) {
        if (!execFlag) {
          execFlag = true;

          // バリデートチェック結果判定
          if (status === true) {
            // エラーなし: Submit実行
            return true;
          } else {
            execFlag = false;
            $('.loading').fadeOut(200);
            // エラー発生: Submit中止
            return false;
          }
        }
      },
    });
  });
});

/* --- サイドバー ------------- */
$(function () {
  $('.f-sidebar li button').on('click', function () {
    $(this).toggleClass('is-open');
  });

  $('.f-sidebar li a').on('click', function () {
    $(this).toggleClass('is-active');
  });

  $('.f-sidebar-btn').on('click', function () {
    $(this).toggleClass('is-close');
    $('.f-sidebar').toggleClass('is-close');
    $('.main').toggleClass('is-close');
  });
});

/* trumbowyg
----------------------------------------------------------------------------- */

function put_editor_data() {
  $('textarea[name=\'editor_data\']').html($('.menu-editor').trumbowyg('html'));
  return true;
}

$(function () {
  if ($('.menu-editor').length) {
    const configurations = {
      core: {},
      plugins: {
        semantic: false,
        resetCss: true,
        lang: "ja",
        btns: [
          ["viewHTML"], // html表示モード
          ["undo", "redo"], // 元に戻す,やり直す ※IEでは元に戻せない
          ["formatting"], // フォーマット（段落、引用、h1~h5）
          ["strong", "em", "del"], // 太字,斜体,取消線
          ["link"], // リンク
          ["foreColor", "backColor"], // 文字色・背景色
          ["emoji"], // 絵文字
          ["justifyLeft", "justifyCenter", "justifyRight"], // 左揃え,中央揃え,右揃え,
          ["unorderedList", "orderedList"], // 列挙, 順序有り列挙
          ["horizontalRule"], // 横罫線
          ["removeformat"], // フォーマットの削除
          ["fullscreen"], // 全画面表示
          ["fontsize"], // フォントサイズ
          //                    ['fontfamily'],                                   // フォント変更
        ],
        plugins: {
          colors: {
            colorList: [
              "000000",
              "ffffff",
              "ffc0cb",
              "ee82ee",
              "ffff00",
              "ffa500",
              "d2691e",
              "00ff00",
              "0000ff",
              "00ffff",
              "ff1493",
              "696969",
              "fffafa",
              "ffccd5",
              "edbeed",
              "ffffcc",
              "ffedcc",
              "d1b9a7",
              "00cc00",
              "ccccff",
              "ccffff",
              "ffb114",
              "808080",
              "f8f8ff",
              "ff99aa",
              "ed8eed",
              "ffff99",
              "ffdb99",
              "d1a07d",
              "009900",
              "9999ff",
              "99ffff",
              "34ff14",
              "a9a9a9",
              "fffaf0",
              "ff6680",
              "ed5fed",
              "cccc00",
              "ffc966",
              "d18854",
              "006600",
              "6666ff",
              "66ffff",
              "14efff",
              "c0c0c0",
              "faf0e6",
              "ff3355",
              "ed2fed",
              "999900",
              "ffb833",
              "d1702a",
              "008000",
              "3333ff",
              "33ffff",
              "5314ff",
              "d3d3d3",
              "faebd7",
              "ff002b",
              "ed00ed",
              "666600",
              "ffa500",
              "d15700",
              "003300",
              "0000ff",
              "00ffff",
              "8214ff",
            ],
            displayAsList: true,
          },
          fontsize: {
            sizeList: ["12px", "14px", "16px"],
          },
        },
        autogrow: false,
        urlProtocol: true,
      },
    };
    const demoTextarea = $(".menu-editor");
    demoTextarea.trumbowyg(configurations.plugins);
  }
});

/* メンバーモーダル
----------------------------------------------------------------------------- */
$(function () {
  if ($('.member-modal').length) {
    $('.member-modal-btn').on('click', function () {
      $(this).parents('.member-modal').fadeOut();
    });
  }
});

/* ライトボックス
----------------------------------------------------------------------------- */
// $(document).ready(function(){
//  $(".photo-colorbox").colorbox({rel:'photo-colorbox', maxWidth:"90%", maxHeight:"90%"});
// });

/* 削除確認
----------------------------------------------------------------------------- */
// $(function(){
//    $("button[name='btnDel']").on("click",function(){
//        if (!$('button[name="myconfirm"]').val() && !myconfirm($(this), 'このデータを削除しますか?')){
//            return false;
//        }
//    });
// });
// $(function(){
//    $("button[name='btnDelAll']").on("click",function(){
//        if (!$('button[name="myconfirm"]').val() && !myconfirm($(this), 'すべてのデータを削除しますか?')){
//            return false;
//        }
//    });
// });
$(function () {
  $('submit[name=\'btnDel\'],button[name=\'btnDel\']').on('click', function () {
    const result = window.confirm("削除してもよろしいですか？");
    if (!result) {
      // いいえ押下時、Submit阻止
      return false;
    }
  });
  $('submit[name=\'btnDelOfc1\'],button[name=\'btnDelOfc1\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelOfc2\'],button[name=\'btnDelOfc2\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDel\'],button[name=\'btnDelIns1\']').on('click', function () {
    const result = window.confirm("削除してもよろしいですか？");
    if (!result) {
      // いいえ押下時、Submit阻止
      return false;
    }
  });
  $('submit[name=\'btnDelIns2\'],button[name=\'btnDelIns2\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelIns3\'],button[name=\'btnDelIns3\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelIns4\'],button[name=\'btnDelIns4\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelHsp\'],button[name=\'btnDelHsp\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelDrg\'],button[name=\'btnDelDrg\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelSvc\'],button[name=\'btnDelSvc\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelInt\'],button[name=\'btnDelInt\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelImg\'],button[name=\'btnDelImg\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelFcl\'],button[name=\'btnDelFcl\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelFml\'],button[name=\'btnDelFml\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  $('submit[name=\'btnDelPrb\'],button[name=\'btnDelPrb\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
  // 利用者/従業員予定実績：削除ボタン-利用者予定
  $('submit[name=\'btnDelUserPlan\'],button[name=\'btnDelUserPlan\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  // 利用者/従業員予定実績：削除ボタン-スタッフ予定
  $('submit[name=\'btnDelStfPlan\'],button[name=\'btnDelStfPlan\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  // 利用者/従業員予定実績：(削除)予定戻しボタン-利用者実績
  $('submit[name=\'btnDelUserRcd\'],button[name=\'btnDelUserRcd\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  // 利用者/従業員予定実績：(削除)予定戻しボタン-スタッフ実績
  $('submit[name=\'btnDelStfRcd\'],button[name=\'btnDelStfRcd\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  // 利用者/従業員予定実績：予定キャンセルボタン-利用者予定
  $('submit[name=\'btnCancelUser\'],button[name=\'btnCancelUser\']').on(
    'click',
    function () {
      const result = window.confirm("予定をキャンセルしてもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  $('submit[name=\'btnCxlUserSvc\'],button[name=\'btnCxlUserSvc\']').on(
    'click',
    function () {
      const result = window.confirm("予定をキャンセルしてもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  $('submit[name=\'btnCxlUser\'],button[name=\'btnCxlUser\']').on(
    'click',
    function () {
      const result = window.confirm("予定をキャンセルしてもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  $('submit[name=\'btnCxlStf\'],button[name=\'btnCxlStf\']').on(
    'click',
    function () {
      const result = window.confirm("予定をキャンセルしてもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  // 利用者/従業員予定実績：予定キャンセルボタン-スタッフ予定
  $('submit[name=\'btnCancelStf\'],button[name=\'btnCancelStf\']').on(
    'click',
    function () {
      const result = window.confirm("予定をキャンセルしてもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  // 指示書 PDF削除
  $('submit[name=\'btnDelPdf\'],button[name=\'btnDelPdf\']').on(
    'click',
    function () {
      const result = window.confirm("削除してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );

  // 登録ボタン確認
  $('submit[name=\'btnEntry\'],button[name=\'btnEntry\']').on('click', function () {
    const result = window.confirm("登録してもよろしいですか？");
    if (!result) {
      // いいえ押下時、Submit阻止
      return false;
    }
  });
  // 登録ボタン確認
  $('submit[name=\'btnRead\'],button[name=\'btnRead\']').on('click', function () {
    const result = window.confirm("既読にしますか？");
    if (!result) {
      // いいえ押下時、Submit阻止
      return false;
    }
  });

  // 展開ボタン確認
  $('submit[name=\'btnMakePlan\'],button[name=\'btnMakePlan\']').on(
    'click',
    function () {
      const result = window.confirm("展開処理を実行してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
      console.log('この週間スケジュールを展開クリック');
      const userId = $(".tgt-usr_id").val();
      if (userId == null || userId == '') {
        alert('利用者を指定していません。');
        return false;
      }
    }
  );

  // 利用者一覧 展開ボタン確認
  $('submit[name=\'btnMakePlanAll\'],button[name=\'btnMakePlanAll\']').on(
    'click',
    function () {
      const result = window.confirm("展開処理を実行してもよろしいですか？");
      if (!result) {
        // いいえ押下時、Submit阻止
        return false;
      }
    }
  );
});

/* 実行確認
----------------------------------------------------------------------------- */
// $(function(){
//    $("button[name='btnExec']").on("click",function(){
//        if (!$('button[name="myconfirm"]').val() && !myconfirm($(this), 'このデータを実行しますか?')){
//            return false;
//        }
//    });
// });

/* キャンセル確認
----------------------------------------------------------------------------- */
// $(function(){
//    $("button[name='btnCancel']").on("click",function(){
//        if (!$('button[name="myconfirm"]').val() && !myconfirm($(this), 'キャンセルしますか?')){
//            return false;
//        }
//    });
// });
//

/* ajax呼出し
----------------------------------------------------------------------------- */
$(function () {
  // 入力フィールドからのBlur時の挙動
  $(document).on('blur', 'input,select,textarea', function () {
    switch (true) {
      case $(this).closest('dd').hasClass('f-keyData'):
        var tgUrl = $(this).closest('dd').data('tg_url');
        var script = document.createElement('script');
        script.type = 'text/javascript';

        var query = '';
        sendParam = getVal('f-keyVal');
        for (const key in sendParam) {
          query += query
            ? '&' + key + '=' + sendParam[key]
            : key + '=' + sendParam[key];
        }
        script.src = tgUrl + '&' + query;
        console.log(script.src);

        // モーダルからの反映の場合、500ミリ秒ウェイト
        if ($('#modal-ajax').find('button.btn-modalSearch').length) {
          var timer_process;
          timer_process = setTimeout(function () {
            $('head').append(script);
          }, 500);

          // 即時リクエスト
        } else {
          $('head').append(script);
        }
        break;

      case $(this).closest('td').hasClass('f-keyData'):
        var tgUrl = $(this).closest('td').data('tg_url');
        var script = document.createElement('script');
        script.type = 'text/javascript';

        var query = '';
        sendParam = getVal('f-keyVal');
        for (const key in sendParam) {
          query += query
            ? '&' + key + '=' + sendParam[key]
            : key + '=' + sendParam[key];
        }
        script.src = tgUrl + '&' + query;
        console.log(script.src);

        // モーダルからの反映の場合、500ミリ秒ウェイト
        if ($('#modal-ajax').find('button.btn-modalSearch').length) {
          var timer_process;
          timer_process = setTimeout(function () {
            $('head').append(script);
          }, 500);

          // 即時リクエスト
        } else {
          $('head').append(script);
        }
        break;

      default:
        break;
    }
  });

  // 指示書から反映ボタンクリック時の挙動
  $(document).on('click', '.copy_btn', function () {
    switch (true) {
      case $(this).closest('dd').hasClass('f-keyData'):
        var tgUrl = $(this).closest('dd').data('tg_url');
        var script = document.createElement('script');
        script.type = 'text/javascript';

        var query = '';
        sendParam = getVal('f-keyVal');
        for (const key in sendParam) {
          query += query
            ? '&' + key + '=' + sendParam[key]
            : key + '=' + sendParam[key];
        }
        script.src = tgUrl + '&' + query;
        console.log(script.src);

        // モーダルからの反映の場合、500ミリ秒ウェイト
        if ($('#modal-ajax').find('button.btn-modalSearch').length) {
          let timer_process;
          timer_process = setTimeout(function () {
            $('head').append(script);
          }, 500);

          // 即時リクエスト
        } else {
          $('head').append(script);
        }
        break;

      default:
        break;
    }
  });
});

/* エンターキー挙動
----------------------------------------------------------------------------- */
/* エンター挙動 */
$(function () {
  // path取得
  const path = location.pathname;
  switch (path) {
    // ログイン画面のみ適用
    case '/index.php':
    case '/':
      enterImpersonateTab4login();
      break;

    // その他一般画面用
    default:
      enterImpersonateTab('body');
      break;
  }
});
// ログイン画面以外
function enterImpersonateTab(field) {
  const oObject = $(field).find(
    'input:not(:button):not([type="radio"]),select'
  );
  const objectAry = $.makeArray($(oObject));

  $(oObject).on('keypress', function (e) {
    const c = e.which ? e.which : e.keyCode;
    if (c == 13) {
      // 選択要素取得
      let index = $(oObject).index(this);
      let cNext = '';
      const nLength = $(oObject).length;

      // readonlyとdisabledを飛ばす
      cNext = e.shiftKey ? --index : ++index;
      cNext = !e.shiftKey && cNext > nLength - 1 ? 0 : cNext;
      cNext = e.shiftKey && cNext < 0 ? nLength - 1 : cNext;

      for (i = 0; i < nLength; i++) {
        if (
          $(objectAry[cNext]).attr('readonly') === 'readonly' ||
          $(objectAry[cNext]).prop('disabled') === true ||
          $(objectAry[cNext]).is(':hidden')
        ) {
          cNext = e.shiftKey ? --cNext : ++cNext;
          cNext = !e.shiftKey && cNext > nLength - 1 ? 0 : cNext;
          cNext = e.shiftKey && cNext < 0 ? nLength - 1 : cNext;
        } else {
          break;
        }
      }

      $(objectAry[cNext]).focus();
      e.preventDefault();
    }
  });
}
// ログイン画面専用
function enterImpersonateTab4login() {
  const oObject = $("form").find("input,button");
  const objectAry = $.makeArray($(oObject));

  $(oObject).on('keypress', function (e) {
    const c = e.which ? e.which : e.keyCode;
    if (c == 13) {
      // 選択要素取得
      let index = $(oObject).index(this);
      let cNext = '';

      // Shift押下かで進む or 戻るの判定
      cNext = e.shiftKey ? --index : ++index;
      cNext = e.shiftKey && cNext < 0 ? 0 : cNext;

      if (!($(this).attr('name') === 'btnLogin' && !e.shiftKey)) {
        $(objectAry[cNext]).focus();
        e.preventDefault();
      }
    }
  });
}

// =======================================================================
// お届け先　郵便番号変換関数
// =======================================================================
/* [引数]
 *     無し
 *
 * [戻り値]
 *     無し
 *
 * -----------------------------------------------------------------------
 */
function zipComposition(str) {
  return str
    .replace(/[^0-9０-９]/g, '')
    .replace(/[Ａ-Ｚａ-ｚ０-９]/g, function (s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
    });
}

// =======================================================================
// Validate設定関数
// =======================================================================
/* [引数]
 *     無し
 *
 * [戻り値]
 *     無し
 *
 * -----------------------------------------------------------------------
 */
function setValidate() {
  let execFlag = false;

  $('form').validationEngine({
    promptPosition: 'bottomLeft',
    focusFirstField: true,
    scroll: false,
    onValidationComplete: function (form, status) {
      if (!execFlag) {
        execFlag = true;

        // バリデートチェック結果判定
        if (status === true) {
          // エラーなし: Submit実行
          return true;
        } else {
          execFlag = false;
          // エラー発生: Submit中止
          $('.loading').fadeOut(200);
          return false;
        }
      }
    },
  });
}

// =======================================================================
// scriptLoad関数
// =======================================================================
/* [引数]
 *     無し
 *
 * [戻り値]
 *     無し
 *
 * -----------------------------------------------------------------------
 */
function scriptLoad(url, callback) {
  const script = document.createElement("script");
  script.type = 'text/javascript';

  // only required for IE <9
  if (script.readyState) {
    script.onreadystatechange = function () {
      if (script.readyState === 'loaded' || script.readyState === 'complete') {
        script.onreadystatechange = null;
        callback();
      }
    };

    // Others
  } else {
    script.onload = function () {
      callback();
    };
  }

  script.src = url;
  document.getElementsByTagName('head')[0].appendChild(script);
}
$(function () {
  // 画像クリアボタン押下時
  $(document).on('click', '.f-upload-delete', function () {
    $(this).closest('.upload').remove();
  });
});

let tgtObjConfirm = null;
function myconfirm(obj, str = '', title = '') {
  if (obj) {
    tgtObjConfirm = obj;
  }

  if (!title) {
    $('.is-myconfirm').find('.member-modal-ttl').html('Confirmation');
  } else {
    $('.is-myconfirm').find('.member-modal-ttl').html(title);
  }

  if (str) {
    $('.is-myconfirm').find('#myconfirm-msg').html(str);
  }

  // モーダルを開く
  $('.is-myconfirm').show();

  if ($('button[name="myconfirm"]').val()) {
    console.log($('button[name="myconfirm"]').val());
    tgtObjConfirm.click();
    return true;
  } else {
    return false;
  }
}
$(function () {
  // 「閉じる」クリック時⇒モーダルを閉じる
  $(document).on('click', '.btn-myconfirm-cancel', function () {
    $('.is-myconfirm').hide();
    $('button[name="myconfirm"]').val('');
  });

  // 「OK」クリック時⇒モーダルを閉じる
  $(document).on('click', 'button[name="myconfirm"]', function () {
    $('button[name="myconfirm"]').val('1');
    myconfirm(null);
  });
});

function toggleFullScreen() {
  const doc = window.document;
  const docEl = doc.documentElement;

  const requestFullScreen =
    docEl.requestFullscreen ||
    docEl.mozRequestFullScreen ||
    docEl.webkitRequestFullScreen ||
    docEl.msRequestFullscreen;
  const cancelFullScreen =
    doc.exitFullscreen ||
    doc.mozCancelFullScreen ||
    doc.webkitExitFullscreen ||
    doc.msExitFullscreen;

  if (
    !doc.fullscreenElement &&
    !doc.mozFullScreenElement &&
    !doc.webkitFullscreenElement &&
    !doc.msFullscreenElement
  ) {
    requestFullScreen.call(docEl);
  } else {
    cancelFullScreen.call(doc);
  }
}

// 登録ボタン押下時のローディング描画
// $(function(){
//    $('button[name="btnEntry"]').click(function(){
//        $('.loading').show();
//    })
// })

// 西暦 => 和暦(年号)
function warekinengo(year) {
  const eras = [
    { year: 2018, name: "令和" },
    { year: 1988, name: "平成" },
    { year: 1925, name: "昭和" },
    { year: 1911, name: "大正" },
    { year: 1867, name: "明治" },
  ];

  for (const i in eras) {
    const era = eras[i];
    const baseYear = era.year;
    const eraName = era.name;

    if (year > baseYear) {
      const eraYear = year - baseYear;
      return eraYear;
    }
  }
  return null;
}
// 西暦 => 和暦(年)
function warekiyear(year) {
  const eras = [
    { year: 2018, name: "令和" },
    { year: 1988, name: "平成" },
    { year: 1925, name: "昭和" },
    { year: 1911, name: "大正" },
    { year: 1867, name: "明治" },
  ];

  for (const i in eras) {
    const era = eras[i];
    const baseYear = era.year;
    const eraName = era.name;

    if (year > baseYear) {
      return eraName;
    }
  }
  return null;
}

// 和暦 => 西暦
function seireki(warekiYear) {
  const matches = warekiYear.match(
    "^(明治|大正|昭和|平成|令和)([0-9０-９]+)年$"
  );

  if (matches) {
    const eraName = matches[1];
    let year = parseInt(
      matches[2].replace(/[０-９]/g, function (match) {
        return String.fromCharCode(match.charCodeAt(0) - 65248);
      })
    );

    if (eraName === '明治') {
      year += 1867;
    } else if (eraName === '大正') {
      year += 1911;
    } else if (eraName === '昭和') {
      year += 1925;
    } else if (eraName === '平成') {
      year += 1988;
    } else if (eraName === '令和') {
      year += 2018;
    }

    return year;
  }
  return null;
}

// =======================================================================
// 誕生日（西暦）から年齢算出し年齢欄に設定する
// =======================================================================
/* [使用方法]
 *  DateTimePicker等に下記を記述する
 *    onchange="setAge(this, [設定先クラス名]);"
 * [引数]
 *     this(固定) ：onchangeを記載したタグのelement情報
 *     設定先のクラス名（省略時はtgtAgeに設定する）
 *
 * [戻り値]
 *     無し
 *
 * -----------------------------------------------------------------------
 */
function setAge(object, setClsName = "tgtAge", unit = "") {
  // 年齢計算を行なう
  const birthday = $(object).val();
  const clcAge = ageCalculation(birthday);
  $('.' + setClsName).val(clcAge + unit);
}
// =======================================================================
// 誕生日（西暦）から年齢を計算する関数
// =======================================================================
/* [引数]
 *     生年月日
 *
 * [戻り値]
 *     年齢
 *
 * -----------------------------------------------------------------------
 */
function ageCalculation(birthday) {
  const birthDate = new Date(birthday);
  const nowDate = new Date();
  // 生年月日より生年値を算出する
  const birthNumber =
    birthDate.getFullYear() * 10000 +
    (birthDate.getMonth() + 1) * 100 +
    birthDate.getDate();
  // 現在日より現在値を算出する
  const nowNumber =
    nowDate.getFullYear() * 10000 +
    (nowDate.getMonth() + 1) * 100 +
    nowDate.getDate();
  // 現在値 - 生年値を10000で割る
  return Math.floor((nowNumber - birthNumber) / 10000);
}

// =======================================================================
// 共通モーダル起動関数
// =======================================================================
/* [引数]
 *     無し
 *
 * [戻り値]
 *     無し
 *
 * -----------------------------------------------------------------------
 */

window.addEventListener('load', () => {
  // モーダルダイアログ呼び出し
  $('.modal_open').click(function () {
    const tgUrl = $(this).data("url");
    const dlgName = $(this).data("dialog_name");
    $('.modal_setting').children().remove();

    // ダイアログ起動判定
    const msg = checkModal(tgUrl);
    if (msg != null) {
      alert(msg);
    } else {
      console.log('ダイアログ起動処理');

      const xhr = new XMLHttpRequest();
      xhr.open('GET', tgUrl, true);
      xhr.addEventListener('load', function () {
        console.log(this.response);
        $('.modal_setting').append(this.response);
        $('.' + dlgName).css('display', 'block');
      });
      xhr.send();
    }
  });

  // モーダルクローズ
  $('.modal_close').click(function () {
    //        document.querySelector('.modal_setting').innerHTML = '';
    $('.modal_setting').children().remove();
  });

  // ダイアログ起動判定
  function checkModal(tgtUrl) {
    // 週間スケジュール
    if (tgtUrl.includes('/schedule/week/dialog/edit_dialog.php')) {
      const res = getQuery("user", tgtUrl);
      console.log(res);
      if (res == null || res == '') {
        return '利用者を指定していません。';
      }
    }
    return null;
  }

  // URLから特定クエリを取得
  function getQuery(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, '\\$&');
    const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
    const results = regex.exec(url);
    if (!results) {
      return null;
    }
    if (!results[2]) {
      return '';
    }
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }
});
