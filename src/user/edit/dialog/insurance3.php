<script>
    $(function () {

        $(".ins3_number1").prop('disabled', true);
        $(".ins3_number3").prop('disabled', false);
        $(".ins3_number4").prop('disabled', false);
        $(".ins3_number5").prop('disabled', false);
        $(".ins3_number1").addClass("bg-gray2");
        $(".ins3_number3").removeClass("bg-gray2");
        $(".ins3_number4").removeClass("bg-gray2");
        $(".ins3_number5").removeClass("bg-gray2");

        // 登録ボタン確認
        $('#btnEntry_ins3').on('click', function () {
            // 開始日
            var stNengo1 = $(".ins3_start_nengo").val();
            var stYear1 = $('.ins3_start_year').val();
            var stMonth1 = $('.ins3_start_month').val();
            var stDay1 = $('.ins3_start_dt').val();
            var stSeireki1 = "";
            // 終了日
            var edNengo1 = $(".ins3_end_nengo").val();
            var edYear1 = $('.ins3_end_year').val();
            var edMonth1 = $('.ins3_end_month').val();
            var edDay1 = $('.ins3_end_dt').val();
            var edSeireki1 = "";
            // 開始日の入力チェック
            if (stNengo1 !== "" && stYear1 !== "" && stMonth1 !== "" && stDay1 !== "") {
                var warekiStartYear1 = stNengo1 + stYear1 + "年";
                var seirekiStartYear1 = seireki(warekiStartYear1);
                stSeireki1 = seirekiStartYear1 + ('0' + stMonth1).slice(-2) + ('0' + stDay1).slice(-2);
            } else {
                alert("開始日が入力されていません。");
                return false;
            }

            // 終了日の入力チェック
            if (edNengo1 !== "" && edYear1 !== "" && edMonth1 !== "" && edDay1 !== "") {
                var warekiEndYear1 = edNengo1 + edYear1 + "年";
                var seirekiEndYear1 = seireki(warekiEndYear1);
                edSeireki1 = seirekiEndYear1 + ('0' + edMonth1).slice(-2) + ('0' + edDay1).slice(-2);
                // 終了日＜開始日のチェック
                if (Number(stSeireki1) > Number(edSeireki1)) {
                    alert("終了日は開始日より後に設定してください");
                    return false;
                }
            }
        });

        // 保険名称選択時に法別番号を設定する
        $('.ins3_name').change(function () {
            var name = $(this).val();
            var legal_num = $('#insurance_name [value="' + name + '"]').data('legal_no');
            $('.legal_no').val(legal_num);
            
            if(name === "国保(2割)(20％)" && $(".ins3_type1").val() === "国保"){
                $(".ins_name_note").text("※【注意】2割は特例地域のみ");
            }else{
                $(".ins_name_note").text("");
            }
            
        });
        // 保険区分選択時に保険番号、被保険者番号の切替を行う
        $('.ins3_type1').change(function () {
            var name = $(this).val();
            if (name === "後期高齢者") {
                // 被保険者番号
                $(".ins3_number1").prop('disabled', false);
                $(".ins3_number1").removeClass("bg-gray2");

                // 法別番号
                $(".ins3_number2").prop('disabled', false);
                $(".ins3_number2").removeClass("bg-gray2");

                // 保険記号
                //$(".ins3_number3").prop('disabled', true);
                //$(".ins3_number3").addClass("bg-gray2");
                $(".ins3_number3").prop('disabled', false);
                $(".ins3_number3").removeClass("bg-gray2");

                // 保険番号
                //$(".ins3_number4").prop('disabled', true);
                //$(".ins3_number4").addClass("bg-gray2");
                $(".ins3_number4").prop('disabled', false);
                $(".ins3_number4").removeClass("bg-gray2");

                // 枝番
                //$(".ins3_number5").prop('disabled', true);
                //$(".ins3_number5").addClass("bg-gray2");
                $(".ins3_number5").prop('disabled', false);
                $(".ins3_number5").removeClass("bg-gray2");

                // 特定処置による経過措置
                $(".ins3_select1").prop('disabled', false);
                $(".ins3_select1").removeClass("bg-gray2");

                // 保険名称
                $(".ins3_name").prop('disabled', false);
                $(".ins3_name").removeClass("bg-gray2");

                                                    // 所得区分
                $(".type3_other").hide();
                if ($(".ins3_type3").val() === "不明") {
                    $(".ins3_type3").val("");
                }
            }
            if (name === "社保") {
                // 被保険者番号
                //$(".ins3_number1").prop('disabled', true);
                //$(".ins3_number1").addClass("bg-gray2");
                $(".ins3_number1").prop('disabled', false);
                $(".ins3_number1").removeClass("bg-gray2");

                // 法別番号
                $(".ins3_number2").prop('disabled', false);
                $(".ins3_number2").removeClass("bg-gray2");

                // 保険記号
                $(".ins3_number3").prop('disabled', false);
                $(".ins3_number3").removeClass("bg-gray2");

                // 保険番号
                $(".ins3_number4").prop('disabled', false);
                $(".ins3_number4").removeClass("bg-gray2");

                // 枝番
                $(".ins3_number5").prop('disabled', true);
                $(".ins3_number5").addClass("bg-gray2");

                // 特定処置による経過措置
                $(".ins3_select1").prop('disabled', true);
                $(".ins3_select1").addClass("bg-gray2");

                // 保険名称
                $(".ins3_name").prop('disabled', false);
                $(".ins3_name").removeClass("bg-gray2");

                // 所得区分
                $(".type3_other").hide();
                if ($(".ins3_type3").val() === "不明") {
                    $(".ins3_type3").val("");
                }
            }
            if (name === "公費のみ") {
                // 被保険者番号
                $(".ins3_number1").prop('disabled', true);
                $(".ins3_number1").addClass("bg-gray2");

                // 法別番号
                $(".ins3_number2").prop('disabled', true);
                $(".ins3_number2").addClass("bg-gray2");

                // 保険記号
                $(".ins3_number3").prop('disabled', false);
                $(".ins3_number3").removeClass("bg-gray2");

                // 保険番号
                $(".ins3_number4").prop('disabled', false);
                $(".ins3_number4").removeClass("bg-gray2");

                // 枝番
                $(".ins3_number5").prop('disabled', true);
                $(".ins3_number5").addClass("bg-gray2");

                // 特定処置による経過措置
                $(".ins3_select1").prop('disabled', true);
                $(".ins3_select1").addClass("bg-gray2");

                // 法別番号
                $(".ins3_number3").prop('disabled', false);
                $(".ins3_number3").removeClass("bg-gray2");

                // 保険名称
                $(".ins3_name").prop('disabled', true);
                $(".ins3_name").addClass("bg-gray2");

                // 所得区分
                $(".type3_other").show();
            }
            if (name === "国保" || name === "公害" || name === "労災" || name === "その他") {
                // 被保険者番号
                //$(".ins3_number1").prop('disabled', true);
                //$(".ins3_number1").addClass("bg-gray2");
                $(".ins3_number1").prop('disabled', false);
                $(".ins3_number1").removeClass("bg-gray2");

                // 法別番号
                $(".ins3_number2").prop('disabled', false);
                $(".ins3_number2").removeClass("bg-gray2");

                // 保険記号
                $(".ins3_number3").prop('disabled', false);
                $(".ins3_number3").removeClass("bg-gray2");

                // 保険番号
                $(".ins3_number4").prop('disabled', false);
                $(".ins3_number4").removeClass("bg-gray2");

                // 枝番
                $(".ins3_number5").prop('disabled', false);
                $(".ins3_number5").removeClass("bg-gray2");

                // 特定処置による経過措置
                $(".ins3_select1").prop('disabled', true);
                $(".ins3_select1").addClass("bg-gray2");

                // 保険名称
                $(".ins3_name").prop('disabled', false);
                $(".ins3_name").removeClass("bg-gray2");

                // 所得区分
                $(".type3_other").hide();
                if ($(".ins3_type3").val() === "不明") {
                    $(".ins3_type3").val("");
                }
            }
        });
    });
</script>
<style>
    .ins_note {
    display: block;
    color: #D80000;
    font-size: 75%;
    font-weight: 400;
    margin-top: 0px;
}
</style>
<div class="modal_insurance3 cancel_act">
    <div class="tit">医療保険証</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <tbody>
            <tr>
                <th class="tac"><span class="">開始日</span></th>
                <td>
                    <select name="upDummy[ins3_start_nengo]" id="era_list" class="ins3_start_nengo w10">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins3_start_year]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w20 ins3_start_year" placeholder="半角数字2桁"><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins3_start_month]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w20 ins3_start_month" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins3_start_dt]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w20 ins3_start_dt" placeholder="半角数字2桁"><label for="birth_d">日</label>
                </td>
            </tr>
            <tr>
                <th class="tac">終了日</th>
                <td>
                    <select name="upDummy[ins3_end_nengo]" id="era_list" class="ins3_end_nengo w10">
                        <option value=""></option>
                        <option value="昭和">昭和</option>
                        <option value="平成">平成</option>
                        <option value="令和">令和</option>
                    </select>
                    <input type="text" name="upDummy[ins3_end_year]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_yr" class="validate[maxSize[2],onlyNumberSp] b_ymd w20 ins3_end_year" placeholder="半角数字2桁"><label for="birth_yr">年</label>
                    <input type="text" name="upDummy[ins3_end_month]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_m" class="validate[maxSize[2],onlyNumberSp] b_ymd w20 ins3_end_month" placeholder="半角数字2桁"><label for="birth_m">月</label>
                    <input type="text" name="upDummy[ins3_end_dt]" maxlength="2" pattern="^[0-9]+$" value="" id="birth_d" class="validate[maxSize[2],onlyNumberSp] b_ymd w20 ins3_end_dt" placeholder="半角数字2桁"><label for="birth_d">日</label>
                </td>
            </tr>
            <tr>
                <th class="tac">特定措置による経過措置</th>
                <td>
                    <span><input type="radio" name="upIns3[select1]" class="ins3_select1" value="0" id="ins3_select1_0"><label for="mode1">無</label></span>
                    <span><input type="radio" name="upIns3[select1]" class="ins3_select1" value="1" id="ins3_select1_1"><label for="mode1">有</label></span>
                </td>
            </tr>
            <tr>
                <th class="tac">退職者医療制度区分</th>
                <td>
                    <span><input type="radio" name="upIns3[select2]" class="ins3_select2" value="0" id="ins3_select2_0"><label for="mode1">無</label></span>
                    <span><input type="radio" name="upIns3[select2]" class="ins3_select2" value="1" id="ins3_select2_1"><label for="mode1">有</label></span>
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="">保険区分</span></th>
                <td>
                    <select name="upIns3[type1]" id="health_cat" class="ins3_type1">
                        <option value=""></option>
                        <option value="国保">国保</option>
                        <option value="社保">社保</option>
                        <option value="後期高齢者">後期高齢者</option>
                        <option value="公費のみ">公費のみ</option>
                        <option value="労災">労災</option>
                        <option value="公害">公害</option>
                        <option value="その他">その他</option>
                    </select>
                </td>
                <td>
                    <span class="ins_note red">※記号・番号・枝番の代わりに被保険者番号を入力する<br>場合は保険区分を後期高齢者に設定してください。</span>
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="">本人区分</span></th>
                <td>
                    <select name="upIns3[type2]" id="personal_cat" class="ins3_type2">
                        <option value=""></option>
                        <option value="本人">本人</option>
                        <option value="被扶養者">被扶養者</option>
                        <option value="高齢者">高齢者</option>
                        <option value="義務教育就学前">義務教育就学前</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="">所得区分</span></th>
                <td>
                    <select name="upIns3[type3]" id="income_cat" class="ins3_type3">
                        <option value=""></option>
                        <option value="現役並みⅢ">現役並みⅢ</option>
                        <option value="現役並みⅠ">現役並みⅠ</option>
                        <option value="現役並みⅠ">現役並みⅠ</option>
                        <option value="一般所得者">一般所得者</option>
                        <option value="低所得者Ⅱ">低所得者Ⅱ</option>
                        <option value="低所得者Ⅰ">低所得者Ⅰ</option>
                        <option class="type3_other" value="不明">不明</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="">保険者番号</span></th>
                <td>
                    <input type="text" name="upIns3[number1]" id="insurer_num" class="validate[maxSize[8],onlyNumberSp] ins3_number1" maxlength="8" pattern="^[0-9]+$" value="" placeholder="半角数字8桁以内">
                </td>
            </tr>
            <tr>
                <th class="tac">法別番号</th>
                <td>
                    <input type="text" name="upIns3[number2]" id="legal_num" class="validate[maxSize[2],onlyNumberSp] ins3_number2 legal_no" maxlength="2" pattern="^[0-9]+$" value="" placeholder="半角数字2桁">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="">記号/番号/枝番</span></th>
                <td>
                    <input type="text" name="upIns3[number3]" id="ins_sym"  class="validate[maxSize[10],onlyNumberSp] ins3_number3 w30" maxlength="10" value="">
                    <input type="text" name="upIns3[number4]" maxlength="10" value="" id="ins_num"  class="validate[maxSize[10],onlyNumberSp] ins3_number4 w30" placeholder="半角数字10桁以内">
                    <input type="text" name="upIns3[number5]" maxlength="10" value="" id="ins_branch" class="validate[maxSize[10],onlyNumberSp] ins3_number5 w30" placeholder="半角数字10桁以内">
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="">保険名称</span></th>
                <td>
                    <select name="upIns3[name]" id="insurance_name" class="ins3_name" style="width:350px">
                        <option data-legal_no="" value=""></option>
                        <option data-legal_no="" value="国保(30%)">国保(30%)</option>
                        <option data-legal_no="" value="国保(2割)(20％)">国保(2割)(20％)</option>
                        <option data-legal_no="" value="国保退職者(30％)">国保退職者(30％)</option>
                        <option data-legal_no="74" value="(退)警察特定共済組合(30％)">(退)警察特定共済組合(30％)</option>
                        <option data-legal_no="75" value="(退)公立学校特定共済組合(30％)">(退)公立学校特定共済組合(30％)</option>
                        <option data-legal_no="72" value="(退)国家公務員特定共済組合(30％)">(退)国家公務員特定共済組合(30％)</option>
                        <option data-legal_no="73" value="(退)地方公務員特定共済組合(30％)">(退)地方公務員特定共済組合(30％)</option>
                        <option data-legal_no="63" value="(退)特定健康保険組合(30％)">(退)特定健康保険組合(30％)</option>
                        <option data-legal_no="34" value="(退)日本私立学校振興・共済事業団(30％)">(退)日本私立学校振興・共済事業団(30％)</option>
                        <option data-legal_no="01" value="協会けんぽ(30％)">協会けんぽ(30％)</option>
                        <option data-legal_no="01" value="協会けんぽ(1割)(10％)">協会けんぽ(1割)(10％)</option>
                        <option data-legal_no="01" value="協会けんぽ(2割)(20％)">協会けんぽ(2割)(20％)</option>
                        <option data-legal_no="33" value="警察共済組合(30％)">警察共済組合(30％)</option>
                        <option data-legal_no="34" value="公立学校共済組合(30％)">公立学校共済組合(30％)</option>
                        <option data-legal_no="31" value="国家公務員共済組合(30％)">国家公務員共済組合(30％)</option>
                        <option data-legal_no="07" value="自衛官">自衛官</option>
                        <option data-legal_no="02" value="船員(職務外)">船員(職務外)</option>
                        <option data-legal_no="02" value="船員(職務上)">船員(職務上)</option>
                        <option data-legal_no="06" value="組合管掌(30%)">組合管掌(30%)</option>
                        <option data-legal_no="32" value="地方公務員等共済組合(30％)">地方公務員等共済組合(30％)</option>
                        <option data-legal_no="03" value="日雇い(一般)(20％)">日雇い(一般)(20％)</option>
                        <option data-legal_no="04" value="日雇い(特別)(20％)">日雇い(特別)(20％)</option>
                        <option data-legal_no="34" value="日本私立学校振興・共済事業団(30％)">日本私立学校振興・共済事業団(30％）</option>
                        <option data-legal_no="39" value="後期高齢者1割(10％)">後期高齢者1割(10％)</option>
                        <option data-legal_no="39" value="後期高齢者2割(20％)">後期高齢者2割(20％)</option>
                        <option data-legal_no="39" value="後期高齢者3割(30％)">後期高齢者3割(30％)</option>
                        <option data-legal_no="" value="労災(0％)">労災(0％)</option>
                        <option data-legal_no="" value="公害(0％)">公害(0％)</option>
                    </select>
                </td>
                <td>
                    <span class="ins_note ins_name_note"></span>
                </td>
            </tr>
            <tr>
                <th class="tac"><span class="">職務上の事由</span></th>
                <td>
                    <select name="upIns3[type4]" id="prof_jiyu" class="ins3_type4" style="width:350px">
                        <option value=""></option>
                        <option value="なし">なし</option>
                        <option value="職務上">職務上</option>
                        <option value="下船後3カ月以内">下船後3カ月以内</option>
                        <option value="通勤災害">通勤災害</option>
                    </select>
                </td>
            </tr>
        <input type="hidden" name="upIns3[unique_id]" value="" class="ins3_id">
        </tbody>
    </table>
    <div class="modal_insurance3_btn">
        <button type="submit" id="btnEntry_ins3" name="btnEntry" value="true">この内容で登録する</button>
    </div>
</div>