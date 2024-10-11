<!-- 編集ダイアログ start ※jsに渡す -->
<div class="new_default common_part1 root_commute root_insurance form1 view_machine cancel_act">
    <div class="close close_part">✕<span>閉じる</span></div>
    <div class="sched_tit"><?= $tgtPlan['service_name'] ?> 登録</div>
    <div class="s_detail">
        <div class="box1">
            <p class="mid">日付/時刻</p>
            <p>
                <input type="text" name="date" class="master_date date_dayOnly" value=<?= $tgtPlan['use_day'] ?>>
                <input type="text" name="time_from" placeholder="時間" value=<?= $tgtPlan['start_time'] ?>><small>～</small><input type="text" name="time_to" placeholder="時間" value=<?= $tgtPlan['end_time'] ?>>
            </p>
        </div>
        <div class="box1">
            <p class="mid">利用者</p>
            <p>
                <span class="n_search">Search</span>
                <span class="user_res"><?= $userList[$tgtPlan['user_id']]['name'] ?></span>
                <span class="label_t">(利用者ID: <?= $tgtPlan['user_id'] ?>)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">実施事業所</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff"><?= $ofcList[$tgtPlan['office']]['name'] ?></span>
                <span class="staff_id">(ID:<?= $tgtPlan['office'] ?>)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">対応者</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff"><?= $tgtPlan['staff_name'] ?></span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">サービス内容</p>
            <p>
                <span class="n_search">Search</span>
                <span class="staff"><?= $tgtPlan['service_name'] ?></span>
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
                <span class="n_search display_code1">Search</span>
                <span class="staff"><?= $tgtPlan['base_service_name'] ?>(<?= $tgtPlan['base_service_code'] ?>)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">サービス詳細</p>
            <p>
                <select>
                    <option disabled hidden>選択してください</option>
                    <option>排泄介助<small>[バルーン(テキスト)]</small></option>
                    <option selected>入浴<small>[ストレッチャー浴(テキストテキスト)]</small></option>
                </select>
            </p>
        </div>
    </div>
    <div class="add_sub">
        <p class="mid">加減算</p>
        <ol>
            <li>
                <?php // debug($planList4['add']); ?>
                <?php if (isset($planList4['add'])): ?>
                    <?php $tgtPlanAdd = $planList4['add']; ?>
                    <?php foreach ($tgtPlanAdd as $tgtPlanAddId => $planAddInfo): ?>
                        <?php // debug($planAddInfo); ?>
                        <select>
                            <!--<option disabled hidden>選択してください</option>-->

                            <option disabled hidden>選択してください</option>
                            <?php $select = $tgtPlanAddId['add_name'] == $planAddInfo['add_name'] ? ' selected' : NULL; ?>
                            <option value="<?= $planAddInfo['add_name'] ?>"<?= $select ?>><?= $planAddInfo['add_name'] ?></option>

                        </select>

                        <p class="list_delete l_delete1">Delete</p>
                        <p class="period_txt">
                            <b>期間</b>
                            <input type="text" name="date" class="master_date date_dayOnly" value=<?= $planAddInfo['start_day'] ?>>～<input type="text" name="date" class="master_date date_dayOnly" value=<?= $planAddInfo['end_day'] ?>>
                            <!--<a href="javascript:;" class="f-right">期間変更</a>-->
                            <!--<a href="javascript:;" class="c-red">期間外です。削除しますか？</a>-->
                            <!--<a href="javascript:;" class="c-red">期間登録をしてください</a>-->
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </li>
        </ol>
        <p class="add_btn add_sub_btn">+</p>
    </div>
    <div class="cost">
        <p class="mid">実費</p>
        <?php // debug($planList4['jippi']); ?>
        <!--<div class="display_cost"><label for="cost">実費</label><input type="checkbox" name="cost" id="cost" checked></div>-->
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

                    <?php if (isset($planList4['jippi'])): ?>
                        <?php $tgtPlanJp = $planList4['jippi']; ?>
                    <?php // debug($tgtPlanJp); ?>
                        <?php foreach ($tgtPlanJp as $tgtPlanJpId => $planJpInfo): ?>
                            <td class="type">
                                <b class="sm">種類</b>
                                <select>
                                    <?php foreach ($unInsType as $tgtType => $dummy): ?>
                                        
                                        <?php $select = $tgtType == $planJpInfo['type'] ? ' selected' : NULL; ?>
                                        <option value="<?= $tgtType ?>"<?= $select ?>><?= $tgtType ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="item">
                                <b class="sm">項目名称</b>
                                <select>
                                    <?php foreach ($unInsMst['宿泊・食事'] as $tgtId => $val): ?>
                                        <?php $select = $val['name'] == $planJpInfo['name'] ? ' selected' : NULL; ?>
                                        <option value="<?= $val['name'] ?>"<?= $select ?>><?= $val['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="price">
                                <b class="sm">単価最大7桁</b>
                                <input type="text" name="単価" value="<?= $planJpInfo['price'] ?>">
                            </td>
                            <td class="tax">
                                <b class="sm">消費税<br>区分</b>
                                <select>
                                    <?php foreach ($unInsType as $tgtType => $dummy): ?>
                                        <?php $select = $val['zei_type'] == $planJpInfo['zei_type'] ? ' selected' : NULL; ?>
                                        <option value="<?= $val['zei_type'] ?>"<?= $select ?>><?= $val['zei_type'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="sales_tax">
                                <b class="sm">消費税率</b>
                                <?php // foreach ($unInsType as $tgtType => $dummy): ?>
                                    <input type="text" name="単価" value="<?= $planJpInfo['rate'] ?>"><span>%</span>
                                <?php // endforeach; ?>
                            </td>
                            <td class="d_cate">
                                <b class="sm">控除区分</b>
                                <select>
                                    <?php foreach ($unInsType as $tgtType => $dummy): ?>
                                        <?php $select = $val['subsidy'] == $planJpInfo['subsidy'] ? ' selected' : NULL; ?>
                                        <option value="<?= $val['subsidy'] ?>"<?= $select ?>><?= $val['subsidy'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <td><p class="list_delete l_delete3">Delete</p></td>
                </tr>
            </tbody>
        </table>
        <p class="add_btn addCost">+</p><!-- ←動いてない -->
    </div>

    <div class="s_constrols">
        <p><span class="btn cancel">キャンセル</span></p>
        <p><span class="btn delete">削除</span><span class="btn duplicate-op duplicate1">複製</span><span class="btn save">保存</span></p>
    </div>
    <div class="update">
        最終更新:
        <span class="time"><?= $tgtPlan['update_date'] ?></span>
        <span class="person"><?= $tgtPlan['update_name'] ?></span>
    </div>
</div>
<!-- 編集ダイアログ end ※jsに渡す -->

<!-- 基本サービスコード選択ダイアログ start ⇒共通パーツにする-->
<div class="new_default kantaki_code visit_service cancel_act">	
    <div class="close close_part">✕<span>閉じる</span></div>
    <div class="code_list">
        <?php // debug($svcMst[$tgtPlan['service_name']]); ?>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>サービス名称</th>
                    <th>コード</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($svcMst[$tgtPlan['service_name']] as $baseSvcCode => $val): ?>
                <tr>
                    <td><button>選択</button></td>
                    <td><?= $val ?></td>
                    <td><?= $baseSvcCode ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- 基本サービスコード選択ダイアログ end -->

<!-- 期間登録ダイアログ start 2022年1月19日(水)の中身　⇒日割りの期間登録なので不要-->
<!--<div class="new_default modal_period daily_rate cancel_act">	
    <div class="close close_part">✕<span>閉じる</span></div>
    <div class="sched_tit">期間登録</div>
    <div class="s_detail">
        <div class="box1">
            <p class="mid">サービスコード</p>
            <p>				
                <span class="n_search">Search</span>
                <span class="staff">定期巡回訪看・日割(133114)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">期間登録</p>
            <p>
                <input type="text" name="date" class="master_date date_no-Day" value="2022/03/17"><small>～</small><input type="text" name="date" class="master_date date_no-Day" value="2022/03/31">
            </p>
        </div>
    </div>
    <div class="add_sub">
        <p class="mid">加算・減算</p>
        <ol>
            <li>
                <select>
                    <option disabled hidden>選択してください</option>
                    <option selected>看護小規模緊急時訪問看護加算</option>
                </select>
            </li>
            <li>
                <select>
                    <option disabled hidden>選択してください</option>
                    <option selected>看護小規模緊急時訪問看護加算</option>
                </select>
                <p class="list_delete l_delete1">Delete</p>
            </li>
            <li>
                <select class="default"><option disabled hidden selected>選択してください</option></select>
                <p class="list_delete l_delete1">Delete</p>
            </li>
        </ol>
        <p class="add_btn add_sub_btn">+</p>
    </div>			
    <div class="s_constrols">
        <p><span class="btn cancel">キャンセル</span></p>
        <p><span class="btn save">保存</span></p>
    </div>
</div>-->
<!-- 期間登録ダイアログ end-->

<!-- 加算・減算 期間登録ダイアログ start ⇒ダイアログ化しないで編集画面内で編集、不要-->
<!--<div class="new_default modal_period add_period cancel_act">	
    <div class="close close_part">✕<span>閉じる</span></div>
    <div class="sched_tit">加算・減算 期間登録</div>
    <div class="s_detail">
        <div class="box1">
            <p class="mid">サービスコード</p>
            <p>				
                <span class="n_search">Search</span>
                <span class="staff">看護小規模緊急時訪問看護加算</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">期間登録</p>
            <p>
                <input type="text" name="date" class="master_date date_no-Day" value="2022/03/17"><small>～</small><input type="text" name="date" class="master_date date_no-Day" value="2022/03/31">
            </p>
        </div>
    </div>		
    <div class="s_constrols">
        <p><span class="btn cancel">キャンセル</span></p>
        <p><span class="btn delete">削除</span><span class="btn save">保存</span></p>
    </div>
</div>-->
<!-- 加算・減算 期間登録ダイアログ end-->