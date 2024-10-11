<div class="modal_insurance4 cancel_act">
    <div class="tit">公費</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <tbody>
            <tr>
                <th class="tac">開始日</th>
                <td>
                    <select name="upDummy[ins4_start_nengo]" class="era w20 ins4_start_nengo ins4_start_nengo">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins4_start_year]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins4_start_year" placeholder="半角数字2桁"><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins4_start_month]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins4_start_month" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins4_start_dt]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins4_start_dt" placeholder="半角数字2桁"><label for="birth_d">日</label>
                </td>
            </tr>
            <tr>
                <th class="tac">終了日</th>
                <td>
                    <select name="upDummy[ins4_end_nengo]" class="era w20 ins4_en ins4_end_nengo">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins4_end_year]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins4_end_year" placeholder="半角数字2桁"><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins4_end_month]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins4_end_month" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins4_end_dt]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w10 ins4_end_dt" placeholder="半角数字2桁"><label for="birth_d">日</label>
                    <input type="button" id="endMonth" class="" style="margin-left:30px;";  value="月末まで有効にする" onclick="javascript:addMonth2();">
                </td>
            </tr>
            <tr>
                <th class="tac">法別番号</th>
                <td>
                    <input type="text" name="upIns4[number1]" id="legal_num2" maxlength="10" pattern="^[0-9]+$" value="" class="validate[maxSize[10],onlyNumberSp] ins4_number1" placeholder="半角数字2桁">
                </td>
            </tr>
            <tr>
                <th class="tac">公費名称</th>
                <td>
                    <input type="text" name="upIns4[name]" id="bearer_num" maxlength="32" value="" class="validate[maxSize[32]] ins4_name" placeholder="" style="width:480px">
                </td>
            </tr>
            <tr>
                <th class="tac">負担者番号</th>
                <td>
                    <input type="text" name="upIns4[number2]" id="bearer_num" maxlength="10" pattern="^[0-9]+$" value="" class="validate[maxSize[10],onlyNumberSp] ins4_number2" placeholder="半角数字10桁以内">
                </td>
            </tr>
            <tr>
                <th class="tac">受給者番号</th>
                <td>
                    <input type="text" name="upIns4[number3]" id="jukyusha_num" maxlength="10" pattern="^[0-9]+$" value="" class="validate[maxSize[10],onlyNumberSp] ins4_number3" placeholder="半角数字10桁以内">
                </td>
            </tr>
            <tr>
                <th class="tac">上限額</th>
                <td>
                    <input type="text" name="upIns4[upper_limit]" id="max_am" class="validate[maxSize[11],onlyNumberSp] ins4_upper_limit w50" maxlength="11" pattern="^[0-9]+$" value="" placeholder="半角数字11桁以内"><span class="unit_m w50 ins4_upper_limit">円</span>
                </td>
            </tr>
            <tr>
                <th class="tac">負担割合</th>
                <td>
                    <input type="text" name="upIns4[rate]" id="ratio" class="validate[maxSize[3],onlyNumberSp] ins4_rate w50" maxlength="3" pattern="^[0-9]+$" value="" placeholder="半角数字3桁以内"><span class="unit_m ins4_rate">％</span>
                </td>
            </tr>
            <input type="hidden" name="upIns4[unique_id]" value="" class="ins4_id">
        </tbody>
    </table>
    <div class="modal_insurance4_btn">
        <button type="submit" name="btnEntry" value="true">この内容で登録する</button>
    </div>
</div>