<div class="modal_introduct1 cancel_act">
    <div class="tit">流入流出情報(第1紹介機関)</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <tbody>
            <tr>
                <th class="tac">第1紹介機関名</th>
                <td>
                    <input type="text" name="upInt[in1_name]" id="institution_n1-1" class="validate[maxSize[30]] institution_n int_in1_name" maxlength="30" style="width: 500px;" value="" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">第1紹介法人名</th>
                <td>
                    <input type="text" name="upInt[in1_company]" id="institution_n1-2" class="validate[maxSize[30]] institution_n int_in1_company" maxlength="30" style="width: 500px;" value="" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">〒/所在地</th>
                <td>
                    <input type="text" name="upInt[in1_post]" id="institution_add-num1" pattern="\d{3}-?\d{4}" value="" class="validate[maxSize[8]] w20 int_in1_post" placeholder="半角数字3桁-半角数字4桁">
                    <input type="text" name="upInt[in1_address]" id="institution_add-txt1" maxlength="50" value="" class="validate[maxSize[50]] w50 int_in1_address" style="width: 500px;" placeholder="50文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">電話番号</th>
                <td>
                    <input type="tel" name="upInt[in1_tel]" id="bango1" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="" class="validate[maxSize[13]] int_in1_tel" placeholder="半角数字と-ハイフン">
                </td>
            </tr>
            <tr>
                <th class="tac">FAX</th>
                <td>
                    <input type="tel" name="upInt[in1_fax]" id="fax1" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="" class="validate[maxSize[13]] int_in1_fax" placeholder="半角数字と-ハイフン">
                </td>
            </tr>
            <tr>
                <th class="tac">メールアドレス</th>
                <td>
                    <input type="email" name="upInt[in1_mail]" id="email1" maxlength="100" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="" class="validate[email,maxSize[100]] int_in1_mail" style="width: 500px;" placeholder="半角英数記号100文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">担当者1</th>
                <td>
                    <input type="text" name="upInt[in1_person1]" id="incharge1-1" maxlength="30" value="" class="validate[maxSize[30]] int_in1_person1" style="width: 300px;" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">担当者2</th>
                <td>
                    <input type="text" name="upInt[in1_person2]" id="incharge1-2" maxlength="30" value="" class="validate[maxSize[30]] int_in1_person2" style="width: 300px;" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">担当者3</th>
                <td>
                    <input type="text" name="upInt[in1_person3]" id="incharge1-3" maxlength="30" value="" class="validate[maxSize[30]] int_in1_person3" style="width: 300px;" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">サービス開始予定日</th>
                <td>
                    <input type="text" name="upInt[in1_start]" class="validate[] master_date date_no-Day int_in1_start" id="" value="" placeholder="">
                </td>
            </tr>
            <tr>
                <th class="tac">備考</th>
                <td>
                    <input type="text" name="upInt[in1_remarks]" maxlength="100" value="" style="width: 700px;" class="validate[maxSize[100]] int_in1_remarks" placeholder="100文字以内">
                </td>
            </tr>
            <input type="hidden" name="upInt[unique_id]" value="" class="int_id">
        </tbody>
    </table>
    <div class="modal_introduct_btn">
        <button type="submit" name="btnEntry" value="true">この内容で登録する</button>
    </div>
</div>