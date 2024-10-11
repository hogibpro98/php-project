<div class="modal_hospital cancel_act">
    <div class="tit">履歴管理</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <tbody>
            <tr>
                <th class="tac"><span class="label_y">開始</span>/終了</th>
                <td>
                    <input type="text" name="upHsp[start_day]" class="validate[required] master_date date_no-Day hsp_start_day" value="" placeholder="">
                    <small>～</small>
                    <input type="text" name="upHsp[end_day]" class="master_date date_no-Day hsp_end_day" value="" placeholder="">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">指示書発行</span></th>
                <td>
                    <input type="checkbox" name="upHsp[select1]" value="1" id="shijisho_hakko" class="hsp_select1">
                    <span class="label_t"><label for="shijisho_hakko">指示書発行</label></span>
                </td>
            </tr>
            <tr>
                <th class="tac">病院/在宅</th>
                <td>
                    <select name="upHsp[type1]" id="attend_cat" class="hsp_type1">
                        <option value=""></option>
                        <option value="病院">病院</option>
                        <option value="在宅">在宅</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">医療機関名称</span></th>
                <td>
                    <input type="text" name='upHsp[name]' id="med_institution-n" maxlength="30" value="" class="validate[maxSize[30]] note1 hsp_name" style="width:300px;" placeholder="30文字以内" onchange="changeHsp(value, 'med_institution-n')"><span>帳票類の印刷時に出力されます</span>
                </td>
            </tr>
            <tr>
                <th class="tac">レセプト出力用名称</th>
                <td>
                    <input type="text" name="upHsp[disp_name]" id="receipt_name2" maxlength="16" value="" class="validate[maxSize[16]] hsp_disp_name bg-gray2" style="width:300px;" readonly="true" placeholder="16文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">主治医</span></th>
                <td>
                    <input type="text" name="upHsp[doctor]" id="attentding_name" maxlength="30" value="" class="validate[maxSize[30]] hsp_doctor" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">所在地</th>
                <td>
                    <input type="text" name="upHsp[address]" id="location" maxlength="50" value="" class="validate[maxSize[50]] hsp_address" style="width:600px;" placeholder="50文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">電話番号①</th>
                <td>
                    <input type="tel" name="upHsp[tel1]" id="bango1" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="" class="validate[maxSize[13],phone] hsp_tel1" placeholder="半角数字と-ハイフン">
                </td>
            </tr>
            <tr>
                <th class="tac">電話番号②</th>
                <td>
                    <input type="tel" name="upHsp[tel2]" id="bango2" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="" class="validate[maxSize[13],phone] hsp_tel2" placeholder="半角数字と-ハイフン">
                </td>
            </tr>
            <tr>
                <th class="tac">FAX</th>
                <td>
                    <input type="tel" name="upHsp[fax]" id="fax" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="" class="validate[maxSize[13],phone] hsp_fax" placeholder="半角数字と-ハイフン">
                </td>
            </tr>
            <input type="hidden" name="upHsp[unique_id]" value="" class="hsp_id">
        </tbody>
    </table>
    <div class="modal_hospital_btn">
        <button type="submit" name="btnEntry" value="true">この内容で登録する</button>
    </div>
</div>
