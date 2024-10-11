<div class="modal_introduct3 cancel_act">
    <div class="tit">流入流出情報(流出先)</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <tbody>
            <tr>
                <th class="tac">流出日</th>
                <td>
                    <input type="text" name="upInt[out_day]" class="master_date date_no-Day int_out_day" id="" value="" placeholder="">
                </td>
            </tr>
            <tr>
                <th class="tac">流出機関名</th>
                <td>
                    <input type="text" name="upInt[out_name]" id="agency_name" maxlength="30" style="width: 500px;" value="" class="validate[maxSize[30]] int_out_name" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">担当者</th>
                <td>
                    <input type="text" name="upInt[out_person]" id="manager_n" maxlength="30" value="" class="validate[maxSize[30]] int_out_person" style="width: 300px;" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">流出理由</th>
                <td>
                    <select name="upInt[out_type]" class="int_out_type">
                        <option value=""></option>
                        <option value="なし">なし</option>
                        <option value="逝去">逝去</option>
                        <option value="入院">入院</option>
                        <option value="施設入所">施設入所</option>
                        <option value="その他">その他</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="tac">流出理由</th>
                <td>
                    <textarea name="upInt[out_memo]" class="validate[maxSize[256]] int_out_memo" placeholder="256文字以内"></textarea>
                </td>
            </tr>
            <input type="hidden" name="upInt[unique_id]" value="" class="int_id">
        </tbody>
    </table>
    <div class="modal_introduct_btn">
        <button type="submit" name="btnEntry" value="true">この内容で登録する</button>
    </div>
</div>