<div class="modal_office2 cancel_act">
    <div class="tit">居宅支援事業所履歴</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <tbody>
            <tr>
                <th class="tac"><span class="label_y">開始</span>/終了</th>
                <td>
                    <input type="date" name="upOfc2[start_day]" class="ofc2_start_day" value="" placeholder="">
                    <small>～</small>
                    <input type="date" name="upOfc2[end_day]" class="ofc2_end_day" value="" placeholder="">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">事業所番号</span></th>
                <td>
                    <span class="n_search office_search" data-url="/user/edit/dialog/office_search_dialog.php" data-set_name="modal_setting2" data-dialog_name="modal_office_search">Search</span>
                    <input type="text" name="upOfc2[office_code]" id="office_no" class="validate[maxSize[10]] ofc2_office_code" maxlength="10" pattern="^[a-zA-Z0-9]+$" value="" placeholder="半角英数字10文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">事業所名称</span></th>
                <td>
                    <input type="text" name="upOfc2[office_name]" class="validate[maxSize[30]] business_name ofc2_office_name" maxlength="30" maxlength="30" value="" placeholder="30文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac">所在地</th>
                <td>
                    <input type="text" name="upOfc2[address]" id="address" class="validate[maxSize[256]] ofc2_address" maxlength="256" value="" placeholder="50文字以内">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">電話番号</span></th>
                <td>
                    <input type="tel" id="bango" name="upOfc2[tel]" class="validate[phone,maxSize[13]] ofc2_tel" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="" placeholder="半角数字と-ハイフン">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">FAX</span></th>
                <td>
                    <input type="tel" name="upOfc2[fax]" id="fax" class="validate[phone,maxSize[13]] ofc2_fax" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="" placeholder="半角数字と-ハイフン">
                </td>
            </tr>
            <tr>
                <th class="tac">届出年月日</th>
                <td>
                    <input type="date" name="upOfc2[found_day]" class="validate[]" value="" placeholder="">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">担当者</span></th>
                <td>
                    <input type="text" name="upOfc2[person_name]" id="manager" class="validate[maxSize[30]] ofc2_person_name" maxlength="30" value="" placeholder="30文字以内" style="width:500px;">
                </td>
            </tr>
            <tr>
                <th class="tac">担当者(カナ)</th>
                <td>
                    <input type="text" name="upOfc2[person_kana]" id="manager-k" class="validate[maxSize[30],onlyKana] ofc2_person_kana" maxlength="30" placeholder="全角カナ30文字以内" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="" style="width:500px;">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="label_y">計画作成区分</span></th>
                <td>
                    <select name="upOfc2[plan_type]" id="plan_div" class="ofc2_plan_type">
                        <option value=""></option>
                        <option value="居宅支援作成">居宅支援作成</option>
                        <option value="自己作成">自己作成</option>
                        <option value="予防支援作成">予防支援作成</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="tac">中止理由</th>
                <td>
                    <select name="upOfc2[cancel_type]" id="med_facility" class="ofc2_cancel_type">
                        <option value=""></option>
                        <option value="非該当">非該当</option>
                        <option value="医療機関入金">医療機関入金</option>
                        <option value="死亡">死亡</option>
                        <option value="その他">その他</option>
                        <option value="介護老人福祉施設入所">介護老人福祉施設入所</option>
                        <option value="介護老人保健施設入所">介護老人保健施設入所</option>
                        <option value="介護療養型医療施設入所">介護療養型医療施設入所</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="tac">中止理由備考</th>
                <td>
                    <input type="text" name="upOfc2[cancel_memo]" id="cancel_riyu" class="validate[maxSize[256]] ofc2_cancel_memo" value="" placeholder="256文字以内">
                </td>
            </tr>
        <input type="hidden" name="upOfc2[unique_id]" value="" class="ofc2_id">
        </tbody>
    </table>
    <div class="modal_office2_btn">
        <button type="submit" name="btnEntry" value="true">この内容で登録する</button>
    </div>

    <script>
        $(function () {
            $(".office_search").on("click", function () {
                var tgUrl = $(this).data('url');
                var dlgName = $(this).data('dialog_name');
                if (!dlgName) {
                    dlgName = "dynamic_modal";
                }
                var setElmentName = $(this).data('dialog_name');
                let formNode = $(this).closest('form');
                var method = formNode.attr('method');
                var settingName = $(this).data('set_name');
                if (!settingName) {
                    settingName = 'modal_setting2';
                }

                let modalNode = document.getElementsByClassName(settingName);
                let node = modalNode.lastElementChild;
                if (node || node !== undefined) {
                    node.lastElementChild.remove();
                }

                // ajaxにて動的にダイアログの内容を取得する
                let xhr = new XMLHttpRequest();
                xhr.open('GET', tgUrl, true);
                xhr.addEventListener('load', function () {
                    console.log(this.response);
                    $("." + settingName).append(this.response);
                    $("." + dlgName).css("display", "block");
                });
                xhr.send();
            });
        });
    </script>
</div>