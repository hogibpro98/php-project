<!-- 編集ダイアログ start -->
<div class="new_default displayed_part root_commute root_insurance form1 view_machine cancel_act">
    <div class="close close_part">✕<span>閉じる</span></div>
    <div class="sched_tit">看多機 通い 登録</div>
    <div class="s_detail">
        <div class="box1">
            <p class="mid">日付/時刻</p>
            <p>
                <input type="text" name="date" class="master_date date_dayOnly" value="2021/01/01(火)">
                <input type="text" name="time_from" placeholder="時間" value="09:00"><small>～</small><input type="text" name="time_to" placeholder="時間" value="11:05">
            </p>
        </div>
        <div class="box1">
            <p class="mid">利用者</p>
            <p>
                <span class="user_res">10かえりえ 炭治郎</span>
                <span class="label_t">(利用者ID: tamag10)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">実施事業所</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff">本社</span>
                <span class="staff_id">(ID:0000001)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">対応者</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff">川北 志乃</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">サービス内容</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff">看多機 通い</span>
            </p>
            <p class="own_expense new_line">
                <span><input type="checkbox" name="own_expense" id="expense" checked><label for="expense">自費</label></span>
                <span class="expense_val">
                    <input type="text" name="cost_val" value="10000"><label>円</label>
                </span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">基本サービスコード</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff">看護小規模１１・日割(771112)</span>
            </p>
        </div>
        <div class="box_i">
            <div class="box1">
                <p class="mid">日割期間/加減算</p>
            </div>
            <div class="box1">
                <p class="mid">期間</p>
                <p><span>2022/03/17～2022/03/31</span><a href="javascript:;">期間変更</a></p>
            </div>
            <div class="box1">
                <p class="mid">加減算</p>				
                <ol>
                    <li>医療減算（日割）</li>
                    <li>特指示減算（日割）</li>
                </ol>
                <p><a href="javascript:;">編集</a><p>
            </div>
        </div>
    </div>
    <div class="add_sub">
        <p class="mid">加減算</p>
        <ol>
            <li>
                <select>
                    <option disabled hidden>選択してください</option>
                    <option selected>看護小規模緊急時訪問看護加算</option>
                    <option>看護小規模退院時共同指導加算</option>
                </select>
                <p class="list_delete l_delete1">Delete</p>
                <p class="period_txt">
                    <b>期間</b>
                    <span>2022/03/17</span><small>～</small><span>2022/03/31</span>
                    <a href="javascript:;" class="f-right">期間変更</a>
                    <a href="javascript:;" class="c-red">期間外です。削除しますか？</a>
                </p>
            </li>
            <li>
                <select>
                    <option disabled hidden>選択してください</option>
                    <option>看護小規模緊急時訪問看護加算</option>
                    <option selected>看護小規模退院時共同指導加算</option>
                </select>
                <p class="list_delete l_delete1">Delete</p>
                <p class="period_txt">
                    <a href="javascript:;" class="c-red">期間登録をしてください</a>
                </p>
            </li>
            <li>
                <select class="default"><option disabled hidden selected>選択してください</option></select>
                <p class="list_delete l_delete1">Delete</p>
            </li>
        </ol>
        <p class="add_btn add_sub_btn">+</p>
    </div>
    <div class="cost">
        <div class="display_cost"><label for="cost">実費</label><input type="checkbox" name="cost" id="cost" checked></div>
    </div>
    <div class="s_constrols">
        <p><span class="btn cancel">キャンセル</span></p>
        <p><span class="btn delete">削除</span><span class="btn duplicate-op duplicate1">複製</span><span class="btn save">保存</span></p>
    </div>
    <div class="update">
        最終更新:
        <span class="time">2021/12/23   14:57</span>
        <span class="person">山田 花子</span>
    </div>
</div>
<!-- 編集ダイアログ end -->


<!-- 実費ダイアログ start ※編集ダイアログの中に入れる -->
<div class="new_default actual_cost cancel_act">
    <div class="close close_part">✕<span>閉じる</span></div>
    <div class="sched_tit">実費</div>
    <div class="add_sub">
        <table>
            <thead>
                <tr>
                    <th class="type">種類</th>
                    <th class="item">項目名称</th>
                    <th class="price">単価<br>最大7桁</th>
                    <th class="tax">消費税<br>区分</th>
                    <th class="sales_tax">消費税率</th>
                    <th class="d_cate">控除区分</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="type">
                        <b class="sm">種類</b>
                        <select>
                            <option selected>食事朝</option>
                            <option>食事夕</option>
                            <option>自費</option>
                        </select>
                    </td>
                    <td class="item">
                        <b class="sm">項目名称</b>
                        <select>
                            <option selected>朝食代(刻み食・ミキサー食)</option>
                            <option>夕食</option>
                            <option>訪看サービス自費(交通費含む・1時間未満)</option>
                        </select>
                    </td>
                    <td class="price"><b class="sm">単価最大7桁</b><input type="text" name="単価" value="500"></td>
                    <td class="tax">
                        <b class="sm">消費税<br>区分</b>
                        <select>
                            <option selected>税込</option>
                            <option>税込</option>
                            <option>税込</option>
                        </select>
                    </td>
                    <td class="sales_tax"><b class="sm">消費税率</b><input type="text" name="単価" value="0"><span>%</span></td>
                    <td class="d_cate">
                        <b class="sm">控除区分</b>
                        <select>
                            <option selected>控除対象外</option>
                            <option>控除対象外</option>
                            <option>控除対象外</option>
                        </select>
                    </td>
                    <td><p class="list_delete l_delete3">Delete</p></td>
                </tr>
                <tr>
                    <td class="type">
                        <b class="sm">種類</b>
                        <select>
                            <option>食事朝</option>
                            <option selected>食事夕</option>
                            <option>自費</option>
                        </select>
                    </td>
                    <td class="item">
                        <b class="sm">項目名称</b>
                        <select>
                            <option>朝食代(刻み食・ミキサー食)</option>
                            <option selected>夕食</option>
                            <option>訪看サービス自費(交通費含む・1時間未満)</option>
                        </select>
                    </td>
                    <td class="price"><b class="sm">単価最大7桁</b><input type="text" name="単価" value="500"></td>
                    <td class="tax">
                        <b class="sm">消費税<br>区分</b>
                        <select>
                            <option>税込</option>
                            <option selected>税込</option>
                            <option>税込</option>
                        </select>
                    </td>
                    <td class="sales_tax"><b class="sm">消費税率</b><input type="text" name="単価" value="0"><span>%</span></td>
                    <td class="d_cate">
                        <b class="sm">控除区分</b>
                        <select>
                            <option>控除対象外</option>
                            <option selected>控除対象外</option>
                            <option>控除対象外</option>
                        </select>
                    </td>
                    <td><p class="list_delete l_delete3">Delete</p></td>
                </tr>
                <tr>
                    <td class="type">
                        <b class="sm">種類</b>
                        <select>
                            <option>食事朝</option>
                            <option>食事夕</option>
                            <option selected>自費</option>
                        </select>
                    </td>
                    <td class="item">
                        <b class="sm">項目名称</b>
                        <select>
                            <option>朝食代(刻み食・ミキサー食)</option>
                            <option>夕食</option>
                            <option selected>訪看サービス自費(交通費含む・1時間未満)</option>
                        </select>
                    </td>
                    <td class="price"><b class="sm">単価最大7桁</b><input type="text" name="単価" value="500"></td>
                    <td class="tax">
                        <b class="sm">消費税<br>区分</b>
                        <select>
                            <option>税込</option>
                            <option>税込</option>
                            <option selected>税込</option>
                        </select>
                    </td>
                    <td class="sales_tax"><input type="text" name="単価" value="0"><span>%</span></td>
                    <td class="d_cate">
                        <b class="sm">控除区分</b>
                        <select>
                            <option>控除対象外</option>
                            <option>控除対象外</option>
                            <option selected>控除対象外</option>
                        </select>
                    </td>
                    <td><p class="list_delete l_delete3">Delete</p></td>
                </tr>
            </tbody>
        </table>
        <p class="add_btn addCost">+</p>
    </div>		
    <div class="s_constrols">
        <p><span class="btn cancel">キャンセル</span></p>
        <p><span class="btn save">保存</span></p>
    </div>
</div>
<!-- 実費ダイアログ end -->