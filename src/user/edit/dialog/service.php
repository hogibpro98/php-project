<div class="modal_service cancel_act">
    <div class="tit">サービス開始終了情報</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <tbody>
            <tr>
                <th class="tac">訪問看護期間</th>
                <td>
                    <input type="date" name="upSvc[start_day]" class="svc_start_day" value="">
                    <small>～</small>
                    <input type="date" name="upSvc[end_day]" class="svc_end_day" value="">
                </td>
            </tr>
            <tr>
                <th class="tac">開始区分</th>
                <td>
                    <select name="upSvc[start_type]" id="kaishi_kubun" class="svc_start_type">
                        <option value=""></option>
                        <option value="訪問開始">訪問開始</option>
                    </select>                                
                </td>
            </tr>
            <tr>
                <th class="tac">訪問終了の状況</th>
                <td>
                    <select name="upSvc[cancel_reason]" id="visit_stat" class="svc_cancel_reason">
                        <option value=""></option>
                        <option value="軽快">1:軽快</option>
                        <option value="施設">2:施設</option>
                        <option value="医療機関">3:医療機関</option>
                        <option value="死亡">4:死亡</option>
                        <option value="その他">5:その他</option>
                    </select>                                
                </td>
            </tr>
            <tr>
                <th class="tac">死亡の状況</th>
                <td>
                    <input type="date" name="upSvc[death_day]" class="svc_death_day" value="">
                    <input type="time" name="upSvc[death_time]" class="time svc_death_time" pattern="^([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$" value="">
                    <select name="upSvc[death_place]" class="place svc_death_place w20">
                        <option value=""></option>
                        <option value="自宅">1:自宅</option>
                        <option value="施設">2:施設</option>
                        <option value="病院">3:病院</option>
                        <option value="診療所">4:診療所</option>
                        <option value="その他">5:その他</option>
                    </select>                                    
                </td>
            </tr>
            <tr>
                <th class="tac">中止理由</th>
                <td>
                    <input type="text" name="upSvc[death_reason]" id="cancel_riyu" class="svc_death_reason" maxlength="30" value="">
                </td>
            </tr>
            <input type="hidden" name="upSvc[unique_id]" value="" class="svc_id">
        </tbody>
    </table>
    <div class="modal_insurance_btn">
        <button type="submit" name="btnEntry" value="true">この内容で登録する</button>
    </div>
</div>