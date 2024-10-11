<script>
    $(function () {
        // 登録ボタン確認
        $('#btnEntry').on('click', function () {
            // 認定有効期間(開始日)
            var stNengo1 = $(".ins1_start_nengo").val();
            var stYear1 = $('.ins1_start_year1_1').val();
            var stMonth1 = $('.ins1_start_month1').val();
            var stDay1 = $('.ins1_start_dt1').val();
            var stSeireki1 = "";
            // 認定有効期間(終了日)
            var edNengo1 = $(".ins1_end_nengo").val();
            var edYear1 = $('.ins1_end_year1').val();
            var edMonth1 = $('.ins1_end_month1').val();
            var edDay1 = $('.ins1_end_dt1').val();
            var edSeireki1 = "";
            // 認定有効期間の開始日の入力チェック
            if (stNengo1 !== "" && stYear1 !== "" && stMonth1 !== "" && stDay1 !== "") {
                var warekiStartYear1 = stNengo1 + stYear1 + "年";
                var seirekiStartYear1 = seireki(warekiStartYear1);
                stSeireki1 = seirekiStartYear1 + ('0' + stMonth1).slice(-2) + ('0' + stDay1).slice(-2);
            } else {
                alert("認定有効期間の開始日が入力されていません。");
                return false;
            }

            // 認定有効期間の終了日の入力チェック
            if (edNengo1 !== "" && edYear1 !== "" && edMonth1 !== "" && edDay1 !== "") {
                var warekiEndYear1 = edNengo1 + edYear1 + "年";
                var seirekiEndYear1 = seireki(warekiEndYear1);
                edSeireki1 = seirekiEndYear1 + ('0' + edMonth1).slice(-2) + ('0' + edDay1).slice(-2);
                // 終了日＜開始日のチェック
                if (Number(stSeireki1) > Number(edSeireki1)) {
                    alert("認定有効期間の開始日が終了日より未来に設定されています。");
                    return false;
                }
            }

            // 区分支給限度額管理期間(開始日)
            var stNengo2 = $(".ins1_start_nengo2").val();
            var stYear2 = $('.ins1_start_year2').val();
            var stMonth2 = $('.ins1_start_month2').val();
            var stDay2 = $('.ins1_start_dt2').val();
            var stSeireki2 = "";
            // 区分支給限度額管理期間(終了日)
            var edNengo2 = $(".ins1_end_nengo2").val();
            var edYear2 = $('.ins1_end_year2').val();
            var edMonth2 = $('.ins1_end_month2').val();
            var edDay2 = $('.ins1_end_dt2').val();
            var edSeireki2 = "";
            // 区分支給限度額管理期間の開始日の入力チェック
            if (stNengo2 !== "" && stYear2 !== "" && stMonth2 !== "" && stDay2 !== "") {
                var warekiStartYear2 = stNengo2 + stYear2 + "年";
                var seirekiStartYear2 = seireki(warekiStartYear2);
                stSeireki2 = seirekiStartYear2 + ('0' + stMonth2).slice(-2) + ('0' + stDay2).slice(-2);
            } else {
                alert("区分支給限度額管理期間の開始日が入力されていません。");
                return false;
            }

            // 区分支給限度額管理期間の終了日の入力チェック
            if (edNengo2 !== "" && edYear2 !== "" && edMonth2 !== "" && edDay2 !== "") {
                var warekiEndYear2 = edNengo2 + edYear2 + "年";
                var seirekiEndYear2 = seireki(warekiEndYear2);
                edSeireki2 = seirekiEndYear2 + ('0' + edMonth2).slice(-2) + ('0' + edDay2).slice(-2);
                // 終了日＜開始日のチェック
                if (Number(stSeireki2) > Number(edSeireki2)) {
                    alert("区分支給限度額管理期間の開始日が終了日より未来に設定されています。");
                    return false;
                }
            }
        });

        // 介護保険証-認定有効期間を区分支給限度額管理期間に反映する
        $('#btnCopyIns1').on('click', function () {
            var startNengo1 = $('.ins1_start_nengo').val();
            var startYear1 = $('.ins1_start_year1_1').val();
            var startMonth1 = $('.ins1_start_month1').val();
            var startDt1 = $('.ins1_start_dt1').val();
            var endNengo1 = $('.ins1_end_nengo').val();
            var endYear1 = $('.ins1_end_year1').val();
            var endMonth1 = $('.ins1_end_month1').val();
            var endDt1 = $('.ins1_end_dt1').val();
            $('.ins1_start_nengo2').val(startNengo1);
            $('.ins1_start_year2').val(startYear1);
            $('.ins1_start_month2').val(startMonth1);
            $('.ins1_start_dt2').val(startDt1);
            $('.ins1_end_nengo2').val(endNengo1);
            $('.ins1_end_year2').val(endYear1);
            $('.ins1_end_month2').val(endMonth1);
            $('.ins1_end_dt2').val(endDt1);
        });
        
        $(".dialog_close").on('click', function () {
           $(location).prop("href", location.href);
        });
    });
</script>
<div class="modal_insurance cancel_act">
    <div class="tit">介護保険証</div>
    <div class="close close_part dialog_close">✕<span>閉じる</span></div>
    <table>
        <tbody>
            <tr>
                <th class="tac"><span>認定日</span></th>
                <td>
                    <select name="upDummy[ins1_certif_nengo]" id="era_list" class="ins1_certif_nengo w10">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins1_certif_year]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_certif_year1 ins1_certif_year1_1" placeholder="半角数字2桁"><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins1_certif_month]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_certif_month1" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins1_certif_dt]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_certif_dt1" placeholder="半角数字2桁"><label for="birth_d">日</label>
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">認定有効期間</span></th>
                <td>
                    <select name="upDummy[ins1_start_nengo]" id="era_list" class="ins1_start_nengo w10">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins1_start_year1]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_start_year1 ins1_start_year1_1" placeholder="半角数字2桁" ><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins1_start_month1]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_start_month1" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins1_start_dt1]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_start_dt1" placeholder="半角数字2桁"><label for="birth_d">日</label>
                    <span>～</span>
                    <select name="upDummy[ins1_end_nengo]" id="era_list" class="ins1_end_nengo w10">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins1_end_year1]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_end_year1" placeholder="半角数字2桁"><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins1_end_month1]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_end_month1" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins1_end_dt1]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_end_dt1" placeholder="半角数字2桁"><label for="birth_d">日</label>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <input type="button" id="btnCopyIns1" style="float:right;" value="認定有効期間を区分支給限度額管理期間に反映する">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">区分支給限度額管理期間</span></th>
                <td>
                    <select name="upDummy[ins1_start_nengo2]" id="era_list" class="ins1_start_nengo2 w10">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins1_start_year2]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_start_year2" placeholder="半角数字2桁"><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins1_start_month2]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_start_month2" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins1_start_dt2]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_start_dt2" placeholder="半角数字2桁"><label for="birth_d">日</label>
                    <span>～</span>
                    <select name="upDummy[ins1_end_nengo2]" id="era_list" class="ins1_end_nengo2 w10">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins1_end_year2]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_end_year2" placeholder="半角数字2桁"><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins1_end_month2]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_end_month2" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins1_end_dt2]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins1_end_dt2" placeholder="半角数字2桁"><label for="birth_d">日</label>
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">保険者番号</span></th>
                <td>
                    <input type="text" name="upIns1[insure_no]" id="insurer_num" class="validate[maxSize[8],onlyNumberSp] w20 ins1_insure_no" maxlength="8" pattern="^[0-9]+$" value="" placeholder="半角数字8桁以内">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">被保険者番号</span></th>
                <td>
                    <input type="text" name="upIns1[insured_no]" id="insurer_num" class="validate[maxSize[10],onlyNumberSp] w20 ins1_insured_no" maxlength="10" pattern="^[0-9]+$" value="" placeholder="半角数字10桁以内">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">要介護度</span></th>
                <td>
                    <select name="upIns1[care_rank]" class="ins1_care_rank">
                        <option value=""></option>
                        <option value="非該当">非該当</option>
                        <option value="自立">自立</option>
                        <option value="事業対象者">事業対象者</option>
                        <option value="要支援（経過的要介護）">要支援（経過的要介護）</option>
                        <option value="要支援1">要支援1</option>
                        <option value="要支援2">要支援2</option>
                        <option value="要介護1">要介護1</option>
                        <option value="要介護2">要介護2</option>
                        <option value="要介護3">要介護3</option>
                        <option value="要介護4">要介護4</option>
                        <option value="要介護5">要介護5</option>
                    </select>
                </td>
            </tr>
            <input type="hidden" name="upIns1[unique_id]" value="" class="ins1_id">
        </tbody>
    </table>
    <div class="modal_insurance_btn">
        <button type="submit" id="btnEntry" name="btnEntry" value="true">この内容で登録する</button>
    </div>
</div>