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

  /* -- 緊急連絡先2用 -------------------------- */

  // プルダウンのoption内容をコピー
  const pd22 = $("#municipal2 option").clone();

  // 1→2連動
  $('#prefecture2').change(function () {
    // lv1のvalue取得
    const lv1Val2 = $("#prefecture2").val();

    // municipalのdisabled解除
    $('#municipal2').removeAttr('disabled');

    // 一旦、municipalのoptionを削除
    $('#municipal2 option').remove();

    // (コピーしていた)元のmunicipalを表示
    $(pd22).appendTo('#municipal2');

    // 選択値以外のクラスのoptionを削除
    if (lv1Val2) {
      $('#municipal2 option[class != ' + lv1Val2 + ']').remove();
    }

    // 「▼選択」optionを先頭に表示
    $('#municipal2').prepend(
      '<option value="" selected="selected">▼選択</option>'
    );
  });

  /* -- 緊急連絡先3用 -------------------------- */

  // プルダウンのoption内容をコピー
  const pd23 = $("#municipal3 option").clone();

  // 1→2連動
  $('#prefecture3').change(function () {
    // lv1のvalue取得
    const lv1Val3 = $("#prefecture3").val();

    // municipalのdisabled解除
    $('#municipal3').removeAttr('disabled');

    // 一旦、municipalのoptionを削除
    $('#municipal3 option').remove();

    // (コピーしていた)元のmunicipalを表示
    $(pd23).appendTo('#municipal3');

    // 選択値以外のクラスのoptionを削除
    if (lv1Val3) {
      $('#municipal3 option[class != ' + lv1Val3 + ']').remove();
    }

    // 「▼選択」optionを先頭に表示
    $('#municipal3').prepend(
      '<option value="" selected="selected">▼選択</option>'
    );
  });
});

$(function () {
  // 薬情変更フラグ
  let medInfoChange = false;

  // 利用者メモ変更フラグ
  let userMemoChange = false;

  // 介護保険証モーダル
  $('.ins1-edit').click(function () {
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
    const ins1_certif_nengo = $(this).data("ins1_certif_nengo");
    const ins1_certif_year = $(this).data("ins1_certif_year");
    const ins1_certif_month = $(this).data("ins1_certif_month");
    const ins1_certif_dt = $(this).data("ins1_certif_dt");

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
    $('.ins1_certif_nengo').val(ins1_certif_nengo);
    $('.ins1_certif_year').val(ins1_certif_year);
    $('.ins1_certif_month').val(ins1_certif_month);
    $('.ins1_certif_dt').val(ins1_certif_dt);

    // オープン
    $('.modal_insurance').show();
  });
  $('.modal-insurance .close').click(function () {
    $('.modal_insurance').hide();
  });

  // 居宅支援事業所履歴モーダル
  $('.ofc2-edit').click(function () {
    // 各種データ取得
    const ofc2_id = $(this).data("ofc2_id");
    const ofc2_sdt = $(this).data("ofc2_start_day");
    const ofc2_edt = $(this).data("ofc2_end_day1");
    const ofc2_ono = $(this).data("ofc2_office_no");
    const ofc2_onm = $(this).data("ofc2_office_name");
    const ofc2_add = $(this).data("ofc2_address");
    const ofc2_tel = $(this).data("ofc2_tel");
    const ofc2_fax = $(this).data("ofc2_fax");
    const ofc2_fdt = $(this).data("ofc2_found_day");
    const ofc2_pnm = $(this).data("ofc2_person_name");
    const ofc2_pkn = $(this).data("ofc2_person_kana");
    const ofc2_ptp = $(this).data("ofc2_plan_type");
    const ofc2_ctp = $(this).data("ofc2_cancel_type");
    const ofc2_cmm = $(this).data("ofc2_cancel_memo");

    // 要素書き換え
    $('.ofc2_id').val(ofc2_id);
    $('.ofc2_start_day').val(ofc2_sdt);
    $('.ofc2_end_day').val(ofc2_edt);
    $('.ofc2_office_code').val(ofc2_ono);
    $('.ofc2_office_name').val(ofc2_onm);
    $('.ofc2_address').val(ofc2_add);
    $('.ofc2_tel').val(ofc2_tel);
    $('.ofc2_fax').val(ofc2_fax);
    $('.ofc2_found_day').val(ofc2_fdt);
    $('.ofc2_person_name').val(ofc2_pnm);
    $('.ofc2_person_kana').val(ofc2_pkn);
    $('.ofc2_plan_type').val(ofc2_ptp);
    $('.ofc2_cancel_type').val(ofc2_ctp);
    $('.ofc2_cancel_memo').val(ofc2_cmm);

    // オープン
    $('.modal_office2').show();
  });
  $('.modal-office2 .close').click(function () {
    $('.modal_office2').hide();
  });

  // 医療保険証モーダル
  $('.ins3-edit').click(function () {
    // 各種データ取得
    const ins3_id = $(this).data("ins3_id");
    const ins3_stn = $(this).data("ins3_start_nengo");
    const ins3_sty = $(this).data("ins3_start_year");
    const ins3_stm = $(this).data("ins3_start_month");
    const ins3_std = $(this).data("ins3_start_dt");
    const ins3_edn = $(this).data("ins3_end_nengo");
    const ins3_edy = $(this).data("ins3_end_year");
    const ins3_edm = $(this).data("ins3_end_month");
    const ins3_edd = $(this).data("ins3_end_dt");
    const ins3_sl1 = $(this).data("ins3_select1");
    const ins3_sl2 = $(this).data("ins3_select2");
    const ins3_tp1 = $(this).data("ins3_type1");
    const ins3_tp2 = $(this).data("ins3_type2");
    const ins3_tp3 = $(this).data("ins3_type3");
    const ins3_no1 = $(this).data("ins3_number1");
    const ins3_no2 = $(this).data("ins3_number2");
    const ins3_no3 = $(this).data("ins3_number3");
    const ins3_no4 = $(this).data("ins3_number4");
    const ins3_mo5 = $(this).data("ins3_number5");
    const ins3_nam = $(this).data("ins3_name");
    const ins3_tp4 = $(this).data("ins3_type4");

    // 要素書き換え
    $('.ins3_id').val(ins3_id);
    $('.ins3_start_nengo').val(ins3_stn);
    $('.ins3_start_year').val(ins3_sty);
    $('.ins3_start_month').val(ins3_stm);
    $('.ins3_start_dt').val(ins3_std);
    $('.ins3_end_nengo').val(ins3_edn);
    $('.ins3_end_year').val(ins3_edy);
    $('.ins3_end_month').val(ins3_edm);
    $('.ins3_end_dt').val(ins3_edd);
    $('#ins3_select1_' + ins3_sl1).prop('checked', true);
    $('#ins3_select2_' + ins3_sl2).prop('checked', true);
    $('.ins3_type1').val(ins3_tp1);
    $('.ins3_type2').val(ins3_tp2);
    $('.ins3_type3').val(ins3_tp3);
    $('.ins3_number1').val(ins3_no1);
    $('.ins3_number2').val(ins3_no2);
    $('.ins3_number3').val(ins3_no3);
    $('.ins3_number4').val(ins3_no4);
    $('.ins3_number5').val(ins3_mo5);
    $('.ins3_name').val(ins3_nam);
    $('.ins3_type4').val(ins3_tp4);

    // オープン
    $('.modal_insurance3').show();
  });
  $('.modal_insurance3 .close').click(function () {
    $('.modal_insurance3').hide();
  });

  // 公費モーダル
  $('.ins4-edit').click(function () {
    // 各種データ取得
    const ins4_id = $(this).data("ins4_id");
    const ins4_stn = $(this).data("ins4_start_nengo");
    const ins4_sty = $(this).data("ins4_start_year");
    const ins4_stm = $(this).data("ins4_start_month");
    const ins4_std = $(this).data("ins4_start_dt");
    const ins4_edn = $(this).data("ins4_end_nengo");
    const ins4_edy = $(this).data("ins4_end_year");
    const ins4_edm = $(this).data("ins4_end_month");
    const ins4_edd = $(this).data("ins4_end_dt");
    const ins4_no1 = $(this).data("ins4_number1");
    const ins4_name = $(this).data("ins4_name");
    const ins4_no2 = $(this).data("ins4_number2");
    const ins4_no3 = $(this).data("ins4_number3");
    const ins4_no4 = $(this).data("ins4_upper_limit");
    const ins4_rat = $(this).data("ins4_rate");

    // 要素書き換え
    $('.ins4_id').val(ins4_id);
    $('.ins4_start_nengo').val(ins4_stn);
    $('.ins4_start_year').val(ins4_sty);
    $('.ins4_start_month').val(ins4_stm);
    $('.ins4_start_dt').val(ins4_std);
    $('.ins4_end_nengo').val(ins4_edn);
    $('.ins4_end_year').val(ins4_edy);
    $('.ins4_end_month').val(ins4_edm);
    $('.ins4_end_dt').val(ins4_edd);
    $('.ins4_name').val(ins4_name);
    $('.ins4_number1').val(ins4_no1);
    $('.ins4_number2').val(ins4_no2);
    $('.ins4_number3').val(ins4_no3);
    $('.ins4_upper_limit').val(ins4_no4);
    $('.ins4_rate').val(ins4_rat);

    // オープン
    $('.modal_insurance4').show();
  });
  $('.modal_insurance4 .close').click(function () {
    $('.modal_insurance4').hide();
  });

  // 医療機関モーダル
  $('.hsp-edit').click(function () {
    // 各種データ取得
    const hsp_id = $(this).data("hsp_id");
    const hsp_std = $(this).data("hsp_start_day");
    const hsp_edd = $(this).data("hsp_end_day");
    const hsp_sl1 = $(this).data("hsp_select1");
    const hsp_tp1 = $(this).data("hsp_type1");
    const hsp_nam = $(this).data("hsp_name");
    const hsp_dsp = $(this).data("hsp_disp_name");
    const hsp_doc = $(this).data("hsp_doctor");
    const hsp_add = $(this).data("hsp_address");
    const hsp_tel1 = $(this).data("hsp_tel1");
    const hsp_tel2 = $(this).data("hsp_tel2");
    const hsp_fax = $(this).data("hsp_fax");

    // 要素書き換え
    $('.hsp_id').val(hsp_id);
    $('.hsp_start_day').val(hsp_std);
    $('.hsp_end_day').val(hsp_edd);
    if (hsp_sl1 == 1) {
      $('#shijisho_hakko').prop('checked', true);
    } else {
      $('#shijisho_hakko').prop('checked', false);
    }
    $('.hsp_type1').val(hsp_tp1);
    $('.hsp_name').val(hsp_nam);
    $('.hsp_disp_name').val(hsp_dsp);
    $('.hsp_doctor').val(hsp_doc);
    $('.hsp_address').val(hsp_add);
    $('.hsp_tel1').val(hsp_tel1);
    $('.hsp_tel2').val(hsp_tel2);
    $('.hsp_fax').val(hsp_fax);

    // オープン
    $('.modal_hospital').show();
  });
  $('.modal-hospital .close').click(function () {
    $('.modal_hospital').hide();
  });

  // サービス開始終了情報モーダル
  $('.svc-edit').click(function () {
    // 各種データ取得
    const svc_id = $(this).data("svc_id");
    const svc_std = $(this).data("svc_start_day");
    const svc_edd = $(this).data("svc_end_day");
    const svc_stp = $(this).data("svc_start_type");
    const svc_crs = $(this).data("svc_cancel_reason");
    const svc_ddt = $(this).data("svc_death_day");
    const svc_dtm = $(this).data("svc_death_time");
    const svc_dpl = $(this).data("svc_death_place");
    const svc_drs = $(this).data("svc_death_reason");

    // 要素書き換え
    $('.svc_id').val(svc_id);
    $('.svc_start_day').val(svc_std);
    $('.svc_end_day').val(svc_edd);
    $('.svc_start_type').val(svc_stp);
    $('.svc_cancel_reason').val(svc_crs);
    $('.svc_death_day').val(svc_ddt);
    $('.svc_death_time').val(svc_dtm);
    $('.svc_death_place').val(svc_dpl);
    $('.svc_death_reason').val(svc_drs);

    // オープン
    $('.modal_service').show();
  });
  $('.modal-service .close').click(function () {
    $('.modal_service').hide();
  });

  // 流入流出情報-新規追加
  $('.int-edit').click(function () {
    console.log('流入流出情報-紹介情報モーダル-第1紹介機関編集');
    // 各種データ取得
    const int_id = $(this).data("int_id");
    const int_nam1 = $(this).data("int_in1_name");
    const int_cop1 = $(this).data("int_in1_company");
    const int_pst1 = $(this).data("int_in1_post");
    const int_add1 = $(this).data("int_in1_address");
    const int_tel1 = $(this).data("int_in1_tel");
    const int_fax1 = $(this).data("int_in1_fax");
    const int_mal1 = $(this).data("int_in1_mail");
    const int_ps11 = $(this).data("int_in1_person1");
    const int_ps12 = $(this).data("int_in1_person2");
    const int_ps13 = $(this).data("int_in1_person3");
    const int_str1 = $(this).data("int_in1_start");
    const int_rmk1 = $(this).data("int_in1_remarks");
    const int_nam2 = $(this).data("int_in2_name");
    const int_cop2 = $(this).data("int_in2_company");
    const int_pst2 = $(this).data("int_in2_post");
    const int_add2 = $(this).data("int_in2_address");
    const int_tel2 = $(this).data("int_in2_tel");
    const int_fax2 = $(this).data("int_in2_fax");
    const int_mal2 = $(this).data("int_in2_mail");
    const int_ps21 = $(this).data("int_in2_person1");
    const int_ps22 = $(this).data("int_in2_person2");
    const int_ps23 = $(this).data("int_in2_person3");
    const int_rmk2 = $(this).data("int_in2_remarks");
    const int_otdt = $(this).data("int_out_day");
    const int_otnm = $(this).data("int_out_name");
    const int_otps = $(this).data("int_out_person");
    const int_ottp = $(this).data("int_out_type");
    const int_otmm = $(this).data("int_out_memo");

    // 要素書き換え
    $('.int_id').val(int_id);
    $('.int_in1_name').val(int_nam1);
    $('.int_in1_company').val(int_cop1);
    $('.int_in1_post').val(int_pst1);
    $('.int_in1_address').val(int_add1);
    $('.int_in1_tel').val(int_tel1);
    $('.int_in1_fax').val(int_fax1);
    $('.int_in1_mail').val(int_mal1);
    $('.int_in1_person1').val(int_ps11);
    $('.int_in1_person2').val(int_ps12);
    $('.int_in1_person3').val(int_ps13);
    $('.int_in1_start').val(int_str1);
    $('.int_in1_remarks').val(int_rmk1);
    $('.int_in2_name').val(int_nam2);
    $('.int_in2_company').val(int_cop2);
    $('.int_in2_post').val(int_pst2);
    $('.int_in2_address').val(int_add2);
    $('.int_in2_tel').val(int_tel2);
    $('.int_in2_fax').val(int_fax2);
    $('.int_in2_mail').val(int_mal2);
    $('.int_in2_person1').val(int_ps21);
    $('.int_in2_person2').val(int_ps22);
    $('.int_in2_person3').val(int_ps23);
    $('.int_in2_remarks').val(int_rmk2);
    $('.int_out_day').val(int_otdt);
    $('.int_out_name').val(int_otnm);
    $('.int_out_person').val(int_otps);
    $('.int_out_type').val(int_ottp);
    $('.int_out_memo').val(int_otmm);

    // オープン
    $('.modal_introduct').show();
  });

  // 流入流出情報-紹介情報モーダル-第1紹介機関編集
  $('.int-edit1').click(function () {
    console.log('流入流出情報-紹介情報モーダル-第1紹介機関編集');
    // 各種データ取得
    const int_id = $(this).data("int_id");
    const int_nam1 = $(this).data("int_in1_name");
    const int_cop1 = $(this).data("int_in1_company");
    const int_pst1 = $(this).data("int_in1_post");
    const int_add1 = $(this).data("int_in1_address");
    const int_tel1 = $(this).data("int_in1_tel");
    const int_fax1 = $(this).data("int_in1_fax");
    const int_mal1 = $(this).data("int_in1_mail");
    const int_ps11 = $(this).data("int_in1_person1");
    const int_ps12 = $(this).data("int_in1_person2");
    const int_ps13 = $(this).data("int_in1_person3");
    const int_str1 = $(this).data("int_in1_start");
    const int_rmk1 = $(this).data("int_in1_remarks");
    const int_nam2 = $(this).data("int_in2_name");
    const int_cop2 = $(this).data("int_in2_company");
    const int_pst2 = $(this).data("int_in2_post");
    const int_add2 = $(this).data("int_in2_address");
    const int_tel2 = $(this).data("int_in2_tel");
    const int_fax2 = $(this).data("int_in2_fax");
    const int_mal2 = $(this).data("int_in2_mail");
    const int_ps21 = $(this).data("int_in2_person1");
    const int_ps22 = $(this).data("int_in2_person2");
    const int_ps23 = $(this).data("int_in2_person3");
    const int_rmk2 = $(this).data("int_in2_remarks");
    const int_otdt = $(this).data("int_out_day");
    const int_otnm = $(this).data("int_out_name");
    const int_otps = $(this).data("int_out_person");
    const int_ottp = $(this).data("int_out_type");
    const int_otmm = $(this).data("int_out_memo");

    // 要素書き換え
    $('.int_id').val(int_id);
    $('.int_in1_name').val(int_nam1);
    $('.int_in1_company').val(int_cop1);
    $('.int_in1_post').val(int_pst1);
    $('.int_in1_address').val(int_add1);
    $('.int_in1_tel').val(int_tel1);
    $('.int_in1_fax').val(int_fax1);
    $('.int_in1_mail').val(int_mal1);
    $('.int_in1_person1').val(int_ps11);
    $('.int_in1_person2').val(int_ps12);
    $('.int_in1_person3').val(int_ps13);
    $('.int_in1_start').val(int_str1);
    $('.int_in1_remarks').val(int_rmk1);
    $('.int_in2_name').val(int_nam2);
    $('.int_in2_company').val(int_cop2);
    $('.int_in2_post').val(int_pst2);
    $('.int_in2_address').val(int_add2);
    $('.int_in2_tel').val(int_tel2);
    $('.int_in2_fax').val(int_fax2);
    $('.int_in2_mail').val(int_mal2);
    $('.int_in2_person1').val(int_ps21);
    $('.int_in2_person2').val(int_ps22);
    $('.int_in2_person3').val(int_ps23);
    $('.int_in2_remarks').val(int_rmk2);
    $('.int_out_day').val(int_otdt);
    $('.int_out_name').val(int_otnm);
    $('.int_out_person').val(int_otps);
    $('.int_out_type').val(int_ottp);
    $('.int_out_memo').val(int_otmm);

    // オープン
    $('.modal_introduct1').show();
  });

  // 流入流出情報-紹介情報モーダル-第2紹介機関編集
  $('.int-edit2').click(function () {
    console.log('流入流出情報-紹介情報モーダル-第2紹介機関編集');
    // 各種データ取得
    const int_id = $(this).data("int_id");
    const int_nam1 = $(this).data("int_in1_name");
    const int_cop1 = $(this).data("int_in1_company");
    const int_pst1 = $(this).data("int_in1_post");
    const int_add1 = $(this).data("int_in1_address");
    const int_tel1 = $(this).data("int_in1_tel");
    const int_fax1 = $(this).data("int_in1_fax");
    const int_mal1 = $(this).data("int_in1_mail");
    const int_ps11 = $(this).data("int_in1_person1");
    const int_ps12 = $(this).data("int_in1_person2");
    const int_ps13 = $(this).data("int_in1_person3");
    const int_str1 = $(this).data("int_in1_start");
    const int_rmk1 = $(this).data("int_in1_remarks");
    const int_nam2 = $(this).data("int_in2_name");
    const int_cop2 = $(this).data("int_in2_company");
    const int_pst2 = $(this).data("int_in2_post");
    const int_add2 = $(this).data("int_in2_address");
    const int_tel2 = $(this).data("int_in2_tel");
    const int_fax2 = $(this).data("int_in2_fax");
    const int_mal2 = $(this).data("int_in2_mail");
    const int_ps21 = $(this).data("int_in2_person1");
    const int_ps22 = $(this).data("int_in2_person2");
    const int_ps23 = $(this).data("int_in2_person3");
    const int_rmk2 = $(this).data("int_in2_remarks");
    const int_otdt = $(this).data("int_out_day");
    const int_otnm = $(this).data("int_out_name");
    const int_otps = $(this).data("int_out_person");
    const int_ottp = $(this).data("int_out_type");
    const int_otmm = $(this).data("int_out_memo");

    // 要素書き換え
    $('.int_id').val(int_id);
    $('.int_in1_name').val(int_nam1);
    $('.int_in1_company').val(int_cop1);
    $('.int_in1_post').val(int_pst1);
    $('.int_in1_address').val(int_add1);
    $('.int_in1_tel').val(int_tel1);
    $('.int_in1_fax').val(int_fax1);
    $('.int_in1_mail').val(int_mal1);
    $('.int_in1_person1').val(int_ps11);
    $('.int_in1_person2').val(int_ps12);
    $('.int_in1_person3').val(int_ps13);
    $('.int_in1_start').val(int_str1);
    $('.int_in1_remarks').val(int_rmk1);
    $('.int_in2_name').val(int_nam2);
    $('.int_in2_company').val(int_cop2);
    $('.int_in2_post').val(int_pst2);
    $('.int_in2_address').val(int_add2);
    $('.int_in2_tel').val(int_tel2);
    $('.int_in2_fax').val(int_fax2);
    $('.int_in2_mail').val(int_mal2);
    $('.int_in2_person1').val(int_ps21);
    $('.int_in2_person2').val(int_ps22);
    $('.int_in2_person3').val(int_ps23);
    $('.int_in2_remarks').val(int_rmk2);
    $('.int_out_day').val(int_otdt);
    $('.int_out_name').val(int_otnm);
    $('.int_out_person').val(int_otps);
    $('.int_out_type').val(int_ottp);
    $('.int_out_memo').val(int_otmm);

    // オープン
    $('.modal_introduct2').show();
  });

  // 流入流出情報-紹介情報モーダル-流入流出情報編集
  $('.int-edit3').click(function () {
    console.log('流入流出情報-紹介情報モーダル-流出先編集');
    // 各種データ取得
    const int_id = $(this).data("int_id");
    const int_nam1 = $(this).data("int_in1_name");
    const int_cop1 = $(this).data("int_in1_company");
    const int_pst1 = $(this).data("int_in1_post");
    const int_add1 = $(this).data("int_in1_address");
    const int_tel1 = $(this).data("int_in1_tel");
    const int_fax1 = $(this).data("int_in1_fax");
    const int_mal1 = $(this).data("int_in1_mail");
    const int_ps11 = $(this).data("int_in1_person1");
    const int_ps12 = $(this).data("int_in1_person2");
    const int_ps13 = $(this).data("int_in1_person3");
    const int_str1 = $(this).data("int_in1_start");
    const int_rmk1 = $(this).data("int_in1_remarks");
    const int_nam2 = $(this).data("int_in2_name");
    const int_cop2 = $(this).data("int_in2_company");
    const int_pst2 = $(this).data("int_in2_post");
    const int_add2 = $(this).data("int_in2_address");
    const int_tel2 = $(this).data("int_in2_tel");
    const int_fax2 = $(this).data("int_in2_fax");
    const int_mal2 = $(this).data("int_in2_mail");
    const int_ps21 = $(this).data("int_in2_person1");
    const int_ps22 = $(this).data("int_in2_person2");
    const int_ps23 = $(this).data("int_in2_person3");
    const int_rmk2 = $(this).data("int_in2_remarks");
    const int_otdt = $(this).data("int_out_day");
    const int_otnm = $(this).data("int_out_name");
    const int_otps = $(this).data("int_out_person");
    const int_ottp = $(this).data("int_out_type");
    const int_otmm = $(this).data("int_out_memo");

    // 要素書き換え
    $('.int_id').val(int_id);
    $('.int_in1_name').val(int_nam1);
    $('.int_in1_company').val(int_cop1);
    $('.int_in1_post').val(int_pst1);
    $('.int_in1_address').val(int_add1);
    $('.int_in1_tel').val(int_tel1);
    $('.int_in1_fax').val(int_fax1);
    $('.int_in1_mail').val(int_mal1);
    $('.int_in1_person1').val(int_ps11);
    $('.int_in1_person2').val(int_ps12);
    $('.int_in1_person3').val(int_ps13);
    $('.int_in1_start').val(int_str1);
    $('.int_in1_remarks').val(int_rmk1);
    $('.int_in2_name').val(int_nam2);
    $('.int_in2_company').val(int_cop2);
    $('.int_in2_post').val(int_pst2);
    $('.int_in2_address').val(int_add2);
    $('.int_in2_tel').val(int_tel2);
    $('.int_in2_fax').val(int_fax2);
    $('.int_in2_mail').val(int_mal2);
    $('.int_in2_person1').val(int_ps21);
    $('.int_in2_person2').val(int_ps22);
    $('.int_in2_person3').val(int_ps23);
    $('.int_in2_remarks').val(int_rmk2);
    $('.int_out_day').val(int_otdt);
    $('.int_out_name').val(int_otnm);
    $('.int_out_person').val(int_otps);
    $('.int_out_type').val(int_ottp);
    $('.int_out_memo').val(int_otmm);

    // オープン
    $('.modal_introduct3').show();
  });

  $('.modal-introduct .close').click(function () {
    $('.modal_introduct').hide();
  });

  $('.modal-introduct1 .close').click(function () {
    $('.modal_introduct1').hide();
  });

  $('.modal-introduct2 .close').click(function () {
    $('.modal_introduct2').hide();
  });

  $('.modal-introduct3 .close').click(function () {
    $('.modal_introduct3').hide();
  });

  $('.basic_info .tr4 input.name').click(function () {
    $('.cont_user-dup').show();
  });

  //    // 重複利用者一覧モーダル
  //    $(".no_dup").click(function(){
  //        alert('aaaaaa');
  //        // オープン
  //        $(".cont_user-dup").show();
  //    });
  //    $(".cont_user-dup .close").click(function(){
  //        $(".cont_user-dup").hide();
  //    });
  //
  // 行追加(画像)
  $('.img_add').click(function () {
    const dl_box = $("#img_box");
    //        var dl_box = $("#img_box dl:nth-child(1)");
    const row = $(".imageｰdd").length;
    let dd_new = '';
    dd_new += '<dd>';
    dd_new += '<span></span>';
    dd_new += '<select name="upImg[' + row + '][tag]">';
    dd_new += '';
    dd_new += '</select>';
    dd_new += '<input type="month" name="upImg[+row+][month]" value="">';
    dd_new += '<label>';
    dd_new +=
      '<span class="btn upload"><img src="/common/image/icon_upload.png" alt=""></span>';
    dd_new +=
      '<input type="file" name="" id="<?= $key ?>" style="display:none;">';
    dd_new += '</label>';
    dd_new += '</dd>';
    //        var num = i++;
    //        dd_new += '<dd><span class="num">' + num + '</span><input type="text" placeholder="入力してください"><div class="hov_box"><p><a href="/common/image/sample-photo.jpg" data-lightbox="image-1" class="btn display2">表示</a><button type="submit" class="btn trash">削除</button><label><span class="btn upload"><img src="/common/image/icon_upload.png" alt=""></span><input type="file" name="test-' + num +'" id="test-' + num +'" style="display:none;"></label></p></div></dd>';
    $(dd_new).appendTo(dl_box);
    //        $(dd_new).prependTo(dl_box);
    //        $(".imageｰdd :nth-child(1)").append(dd_new);
  });

  // 行追加(介護保険証)
  //    $(".ins_add1").click(function(){
  //        var tbl = $(".ins_table1");
  //        var tr_new = "";
  //        tr_new += '<tr><td><span class="ng">NG</span></td><td><span></span><small>～</small></td><td></td><td></td><td></td><td><span></span><small>～</small><span></span></td><td><span class="btn trash">削除</span></td></tr>';
  //        $(tr_new).appendTo(tbl);
  //    });

  // 行追加(居宅支援事業所履歴)
  //    $(".ins_add2").click(function(){
  //        var tbl = $(".ins_table2");
  //        var tr_new = "";
  //        tr_new += '<tr><td><span class="ng">NG</span></td><td><span></span><small>～</small></td><td></td><td></td><td></td><td><span></span><small>～</small><span></span></td><td><span class="btn trash">削除</span></td></tr>';
  //        $(tr_new).appendTo(tbl);
  //    });

  // 行追加(給付情報)
  $('.ins_add3').click(function () {
    const tbl = $(".ins_table3");
    const row = ins3.rows.length;
    let tr_new = '';
    tr_new += '<tr>';
    tr_new += '    <td>';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new += '        <div>';
    tr_new +=
      '            <select class="era validate[required]" name="upDummy[ins2][' +
      row +
      '][start_nengo]">';
    tr_new += '                <option value=""></option>';
    tr_new += '                <option value="昭和">昭和</option>';
    tr_new += '                <option value="平成">平成</option>';
    tr_new += '                <option value="令和" selected>令和</option>';
    tr_new += '            </select>';
    tr_new += '            <span>';
    tr_new +=
      '                <input type="text" name="upDummy[ins2][' +
      row +
      '][start_year]" value="" id="birth_yr" class="b_ymd validate[required,maxSize[3]]"><label for="birth_yr">年</label>';
    tr_new += '            </span>';
    tr_new += '            <span>';
    tr_new +=
      '                <input type="text" name="upDummy[ins2][' +
      row +
      '][start_month]" value="" id="birth_m" class="b_ymd validate[required,maxSize[2]]"><label for="birth_m">月</label>';
    tr_new += '            </span>';
    tr_new += '            <span>';
    tr_new +=
      '                <input type="text" name="upDummy[ins2][' +
      row +
      '][start_dt]" value="" id="birth_d" class="b_ymd validate[required,maxSize[2]]"><label for="birth_d">日</label>';
    tr_new += '            </span>';
    tr_new += '        </div>';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new += '        <small>～</small>';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new += '        <div>';
    tr_new +=
      '            <select class="era" name="upDummy[ins2][' +
      row +
      '][end_nengo]">';
    tr_new += '                <option value=""></option>';
    tr_new += '                <option value="昭和">昭和</option>';
    tr_new += '                <option value="平成">平成</option>';
    tr_new += '                <option value="令和" selected>令和</option>';
    tr_new += '            </select>';
    tr_new += '            <span>';
    tr_new +=
      '                <input type="text" name="upDummy[ins2][' +
      row +
      '][end_year]" value="" id="birth_yr" class="b_ymd validate[required,maxSize[3]]"><label for="birth_yr">年</label>';
    tr_new += '            </span>';
    tr_new += '            <span>';
    tr_new +=
      '                <input type="text" name="upDummy[ins2][' +
      row +
      '][end_month]" value="" id="birth_m" class="b_ymd validate[required,maxSize[2]]"><label for="birth_m">月</label>';
    tr_new += '            </span>';
    tr_new += '            <span>';
    tr_new +=
      '                <input type="text" name="upDummy[ins2][' +
      row +
      '][end_dt]" value="" id="birth_d" class="b_ymd validate[required,maxSize[2]]"><label for="birth_d">日</label>';
    tr_new += '            </span>';
    tr_new += '        </div>';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new += '        <span>';
    tr_new +=
      '            <input type="text" name="upIns2[' +
      row +
      '][rate]" id="rate1" value="" class="validate[required,maxSize[3]]" style="width:50px;">';
    tr_new += '        </span>';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new +=
      '        <span class="btn trash ins2_new_del" style="width:60px;">削除</span>';
    tr_new += '    </td>';
    tr_new += '</tr>';
    $(tr_new).prependTo(tbl);
  });
  $('.ins_table3').on('click', '.ins2_new_del', function (event) {
    event.preventDefault();
    $(this).closest('tr').remove();
    return false;
  });

  // 行追加(医療保険証)
  //    $(".ins_add4").click(function(){
  //        var tbl = $(".ins_table4");
  //        var tr_new = "";
  //        tr_new += '<tr><td><span class="ng">NG</span></td><td><span></span><small>～</small><span></span></td><td><span class="btn trash">削除</span></td></tr>';
  //        $(tr_new).appendTo(tbl);
  //    });

  // 行追加(公費)
  //    $(".ins_add5").click(function(){
  //        var tbl = $(".ins_table5");
  //        var tr_new = "";
  //        tr_new += '<tr><td><span></span><small>～</small><span></span></td><td>02</td><td></td><td></td><td></td><td><span class="btn trash">削除</span></td></tr>';
  //        $(tr_new).appendTo(tbl);
  //    });

  // 行追加(医療機関)
  //    $(".med_add1").click(function(){
  //        var tbl = $(".med_table1");
  //        var tr_new = "";
  //        tr_new += '<tr><td><span></span><small>～</small><span></span></td><td></td><td></td><td></td><td></td><td><span class="btn trash">削除</span></td></tr>';
  //        $(tr_new).appendTo(tbl);
  //    });

  // 行追加(薬情)
  $('.med_add2').click(function () {
    const tbl = $(".med_table2");
    const row = drug.rows.length + 1;
    let tr_new = '';
    tr_new += '<tr>';
    tr_new += '    <td>';
    tr_new +=
      '        <input type="text" name="upDrg[' +
      row +
      '][start_day]" class="master_date date_no-Day" value="">';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new +=
      '       <input type="text" name="upDrg[' +
      row +
      '][end_day]" class="master_date date_no-Day" value="">';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new +=
      '        <input type="text" name="upDrg[' +
      row +
      '][drug_name]" class="iyakuhin_n" value="">';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new += '        <select name="upDrg[' + row + '][drug_usage]">';
    tr_new += '            <option hidden disabled selected>&nbsp;</option>';
    tr_new += '            <option></option>';
    tr_new += '            <option value="内服">内服</option>';
    tr_new += '            <option value="外用">外用</option>';
    tr_new += '            <option value="頓服">頓服</option>';
    tr_new += '            <option value="その他">その他</option>';
    tr_new += '        </select>';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new +=
      '        <input type="text" name="upDrg[' +
      row +
      '][dose]" class="amount" value="">';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new +=
      '        <input type="text" name="upDrg[' +
      row +
      '][effect]" class="effect" value="">';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new +=
      '        <input type="text" name="upDrg[' +
      row +
      '][side_effect]" class="side_effect" value="">';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new +=
      '        <input type="text" name="upDrg[' +
      row +
      '][remarks]" class="remark">';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new += '        <span class="btn trash">削除</span>';
    tr_new += '    </td>';
    tr_new += '    <td>';
    tr_new += '    </td>';
    tr_new += '</tr>';
    $(tr_new).prependTo(tbl);
    $('.date_no-Day').datepicker({ dateFormat: 'yy/mm/dd' });
    // 薬情変更フラグをありに設定する
    medInfoChange = true;
  });

  // 行追加(サービス)
  //    $(".med_add3").click(function(){
  //        var tbl = $(".med_table3");
  //        var tr_new = "";
  //        tr_new += '<tr><td><span class="ng"></span></td><td><span></span><small>～</small><span>2021/03/01</span></td><td></td><td><span class="btn trash">削除</span></td></tr>';
  //        $(tr_new).appendTo(tbl);
  //    });

  // 行追加(家族)
  $('.fml_add').click(function () {
    const tbl = $(".kazoku_kosei");
    const row = family.rows.length;
    let tr_new = '';
    tr_new += '<tr>';
    tr_new += '    <td style="width:70px;">';
    tr_new += '    </td>';
    tr_new += '    <td style="width:200px;">';
    tr_new +=
      '    <select name="upFml[' +
      row +
      '][type]" class="em_con-list2" data-key="' +
      row +
      '" onchange="copyFamily(this)">';
    tr_new += '        <option value=""></option>';
    tr_new += '        <option value="1">緊急連絡先①を反映</option>';
    tr_new += '        <option value="2">緊急連絡先②を反映</option>';
    tr_new += '        <option value="3">緊急連絡先③を反映</option>';
    tr_new += '    </select>';
    tr_new +=
      '        <input type="hidden" name="btnFmlCopy" class="copyFml" value="">';
    tr_new += '    </td>';
    tr_new += '    <td style="width:150px;">';
    tr_new +=
      '        <input type="text" name="upFml[' +
      row +
      '][name]" class="name fml-name-' +
      row +
      '" maxlength="30" value="">';
    tr_new += '    </td>';
    tr_new += '    <td style="width:80px;">';
    tr_new +=
      '        <input type="text" name="upFml[' +
      row +
      '][relation_type]" class="relation fml-relation_type-' +
      row +
      '" maxlength="10" value="">';
    tr_new += '    </td>';
    tr_new += '    <td style="width:150px;">';
    tr_new +=
      '        <input type="text" name="upFml[' +
      row +
      '][relation_memo]" class="relation_memo fml-relation_memo-' +
      row +
      '" maxlength="256" value="">';
    tr_new += '    </td>';
    tr_new += '    <td style="width:150px;">';
    tr_new +=
      '        <input type="text" name="upFml[' +
      row +
      '][business]" class="occupation fml-business-' +
      row +
      '" maxlength="30" value="">';
    tr_new += '    </td>';
    tr_new += '    <td style="width:350px;">';
    tr_new +=
      '        <input type="text" name="upFml[' +
      row +
      '][remarks]" class="remark fml-remarks-' +
      row +
      '" value="">';
    tr_new += '    </td>';
    tr_new += '    <td style="width:80px;">';
    tr_new +=
      '        <button type="button" id="btn_del_fml2" class="btn-del" name="btnDelFml2" style="width:70px;" value="削除">削除</button>';
    tr_new += '    </td>';
    tr_new += '</tr>';
    $(tr_new).prependTo(tbl);
  });

  // 家族構成保存前削除
  $('#family').on('click', '#btn_del_fml2', function (event) {
    const result = window.confirm("削除してよろしいですか？");
    if (result) {
      event.preventDefault();
      $(this).closest('tr').remove();
      return false;
    }
  });

  // 行追加(紹介情報)
  //    $(".flow_add").click(function(){
  //        var tbl = $(".flow_tbl");
  //        var tr_new = "";
  //        tr_new += '<tr><td></td><td></td><td></td><td></td><td></td><td><span class="btn trash">削除</span></td></tr>';
  //        $(tr_new).appendTo(tbl);
  //    });

  // 削除
  // $(".list_scroll-x table").on('click','.trash',function(event){
  //    event.preventDefault();
  //    $(this).closest('tr').remove();
  //    return false;
  // });

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

  // 郵便番号存在チェック
  $('#post').blur(function () {
    const postNo = $("#post").val();
    $.ajax({
      async: false,
      type: 'POST',
      url: './ajax/post_check_ajax.php',
      dataType: 'text',
      data: {
        post_no: postNo,
      },
    })
      .done(function (data) {
        console.log('郵便番号存在チェック : ' + data);
        if (data) {
          console.log(data);
        }
        return false;
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log('ajax通信に失敗しました');
        console.log('jqXHR          : ' + jqXHR.status); // HTTPステータスが取得
        console.log('textStatus     : ' + textStatus); // タイムアウト、パースエラー
        console.log('errorThrown    : ' + errorThrown.message); // 例外情報
      });
  });

  // 画面遷移時に発火
  window.onbeforeunload = function (event) {
    if (medInfoChange) {
      event.preventDefault();
      event.returnValue =
        '薬情が保存されていません。ページを離れてよろしいですか？';
      console.log(event.returnValue);
    }
  };

  // 保存ボタン押下
  $('#btnEntry').on('click', function () {
    // 保存押下で編集なしとする
    medInfoChange = false;
    userMemoChange = false;
  });

  $('.med_col').on('change', function () {
    // 薬情項目変更時に編集ありとする
    medInfoChange = true;
    console.log('薬情項目変更');
  });

  // 利用者メモ変更時
  $('.userRemarks').on('change', function () {
    const userId = getUrlParam("user");
    const remarks = $(".userRemarks").val();

    // 未登録ユーザーの場合は処理しない
    if (!userId) {
      return false;
    }

    // 利用者メモ更新処理
    $.ajax({
      async: false,
      type: 'POST',
      url: './ajax/user_memo_ajax.php',
      dataType: 'text',
      data: {
        user_id: userId,
        remarks: remarks,
      },
    })
      .done(function (data) {})
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log('ajax通信に失敗しました');
        console.log('jqXHR          : ' + jqXHR.status); // HTTPステータスが取得
        console.log('textStatus     : ' + textStatus); // タイムアウト、パースエラー
        console.log('errorThrown    : ' + errorThrown.message); // 例外情報
      });
  });

  // 基本情報-履歴管理
  $('#med_institution-n').on('change', function () {
    const medInstitution = $("#med_institution-n").val();
    $('#receipt_name').val(medInstitution);

    const count = medInstitution.length;
    if (count >= 16) {
      // 16文字以上の場合は、レセプト出力名称を編集可にする。
      $('#receipt_name').removeAttr('readOnly');
      $('#receipt_name').removeClass('bg-gray2');
    }
  });

  // 全角入力不可
  $('.non_zenkaku').on('input', function () {
    if (/^[a-zA-Z0-9\-_]+$/.test(this.value)) {
      return true;
    } else {
      this.value = '';
    }
  });

  $('.standard_last_kana').on('change', function () {
    checkDupList();
  });
  $('.standard_first_kana').on('change', function () {
    checkDupList();
  });
  $('.standard_wareki').on('change', function () {
    checkDupList();
  });
  $('.standard_year').on('change', function () {
    checkDupList();
  });
  $('.standard_month').on('change', function () {
    checkDupList();
  });
  $('.standard_day').on('change', function () {
    checkDupList();
  });

  // 契約事業所新規登録
  $('.office3').on('click', function () {
    // モーダルダイアログ呼び出し
    const userId = getUrlParam("id");
    const dlgName = "dynamic_modal";
    const tgUrl = "/user/edit/dialog/office3.php?user_id=" + userId;

    const modalNode = document.getElementsByClassName("modal_setting");
    const node = modalNode.lastElementChild;
    if (node !== undefined) {
      node.lastElementChild.remove();
    }
    const xhr = new XMLHttpRequest();
    xhr.open('GET', tgUrl, true);
    xhr.addEventListener('load', function () {
      if (this.response) {
        console.log(this.response);
        $('.modal_setting').append(this.response);
        $('.' + dlgName).css('display', 'block');
      }
    });
    xhr.send();
  });

  $('.tit_toggle2').on('click', function () {
    //        $(this).next().slideToggle();

    $('.hist_list').toggle();
  });
});

function convSeireki() {
  console.log('convSeireki');
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

// 認定有効期間をコピー
function periodCopy() {
  const start_nengo = $(".tgt_sn1").val();
  const start_year = $(".tgt_sy1").val();
  const start_month = $(".tgt_sm1").val();
  const start_day = $(".tgt_sd1").val();
  const end_nengo = $(".tgt_en1").val();
  const end_year = $(".tgt_ey1").val();
  const end_month = $(".tgt_em1").val();
  const end_day = $(".tgt_ed1").val();

  $('.tgt_sn2').val(start_nengo);
  $('.tgt_sy2').val(start_year);
  $('.tgt_sm2').val(start_month);
  $('.tgt_sd2').val(start_day);
  $('.tgt_en2').val(end_nengo);
  $('.tgt_ey2').val(end_year);
  $('.tgt_em2').val(end_month);
  $('.tgt_ed2').val(end_day);
}

// １ヶ月有効にする(1か月後)
function addMonth() {
  const start_nengo = $(".ins4_sn").val();
  const start_year = $(".ins4_sy").val();
  const start_month = $(".ins4_sm").val();
  const start_day = $(".ins4_sd").val();
  const tgt_year = seireki(start_nengo + start_year + "年");

  const dt = new Date(tgt_year + "/" + start_month + "/" + start_day);
  dt.setMonth(dt.getMonth() + 1);

  $('.ins4_en').val(warekiyear(dt.getFullYear()));
  $('.ins4_ey').val(warekinengo(dt.getFullYear()));
  $('.ins4_em').val(dt.getMonth() + 1);
  $('.ins4_ed').val(dt.getDate());
}

// １ヶ月有効にする(月末設定)
function addMonth2() {
  const start_nengo = $(".ins4_start_nengo").val();
  const start_year = $(".ins4_start_year").val();
  const start_month = $(".ins4_start_month").val();
  const start_day = $(".ins4_start_dt").val();
  const tgt_year = seireki(start_nengo + start_year + "年");

  const dt = new Date(tgt_year + "/" + start_month + "/" + start_day);
  // 日付に1を設定します.
  dt.setDate(1);
  dt.setMonth(dt.getMonth() + 1);
  dt.setDate(0);

  $('.ins4_end_nengo').val(warekiyear(dt.getFullYear()));
  $('.ins4_end_year').val(warekinengo(dt.getFullYear()));
  $('.ins4_end_month').val(dt.getMonth() + 1);
  $('.ins4_end_day').val(dt.getDate());
}

// 指示書検索
function changeSijisyo(value) {
  value = value == 1 ? 0 : 1;
  const url = new URL(location.href);
  if (!url.searchParams.get('tab')) {
    url.searchParams.append('tab', '4');
  } else {
    url.searchParams.set('tab', '4');
  }
  if (!url.searchParams.get('search[sijisyo]')) {
    url.searchParams.append('search[sijisyo]', value);
  } else {
    url.searchParams.set('search[sijisyo]', value);
  }
  location.href = url;
}

// 終了分表示
function changeYakujo(value) {
  value = value == 1 ? 0 : 1;
  const url = new URL(location.href);
  if (!url.searchParams.get('tab')) {
    url.searchParams.append('tab', '4');
  } else {
    url.searchParams.set('tab', '4');
  }
  if (!url.searchParams.get('search[drg_disp_flg]')) {
    url.searchParams.append('search[drg_disp_flg]', value);
  } else {
    url.searchParams.set('search[drg_disp_flg]', value);
  }
  location.href = url;
}

// 家族コピー
function copyFamily(obj) {
  // セレクトで選択した値
  const idx = obj.selectedIndex;
  const slct = obj.options[idx].value;

  // data-key
  const seq = $(obj).data("key");

  // コピー元の情報を取得
  const name = $(".emg-name-" + slct).val();
  const type = $(".emg-relation_type-" + slct).val();
  const memo = $(".emg-relation_memo-" + slct).val();
  const rmks = $(".emg-remarks-" + slct).val();

  // コピー先へ展開
  $('.fml-name-' + seq).val(name);
  $('.fml-relation_type-' + seq).val(type);
  $('.fml-relation_memo-' + seq).val(memo);
  $('.fml-remarks-' + seq).val(rmks);
}

function checkDupList() {
  const lname = $(".standard_last_kana").val();
  const fname = $(".standard_first_kana").val();
  const erayr = $(".standard_wareki").val();
  const birth_yr = $(".standard_year").val();
  const birth_m = $(".standard_month").val();
  const birth_d = $(".standard_day").val();

  if (!lname || !fname) {
    return false;
  }
  if (!birth_yr || !birth_m || !birth_d) {
    return false;
  }

  // 生年月日（西暦）
  const birthday = birth_yr + "-" + birth_m + "-" + birth_d;
  // モーダルダイアログ呼び出し
  const userId = getUrlParam("id");
  const dlgName = "dynamic_modal";
  const tgUrl =
    "/user/edit/dialog/duplication.php?user_id=" +
    userId +
    "&birthday=" +
    birthday +
    "&first_kana=" +
    fname +
    "&last_kana=" +
    lname;
  console.log(tgUrl);

  const modalNode = document.getElementsByClassName("modal_setting");
  const node = modalNode.lastElementChild;
  if (node !== undefined) {
    node.lastElementChild.remove();
  }
  const xhr = new XMLHttpRequest();
  xhr.open('GET', tgUrl, true);
  xhr.addEventListener('load', function () {
    if (this.response) {
      console.log(this.response);
      $('.modal_setting').append(this.response);
      $('.' + dlgName).css('display', 'block');
    }
  });
  xhr.send();
}

// URLから特定クエリを取得
function getUrlParam(name) {
  url = window.location.href;
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

// 重複利用者一覧モーダル
function openDupList() {
  // $(".cont_user-dup").show();
}
