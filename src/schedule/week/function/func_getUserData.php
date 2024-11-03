<?php
/* =============================================================================
 * 利用者データ取得関数
 * =============================================================================
 */
function getUserData()
{
    $sql = "";
    $sql .= " SELECT ";
    $sql .= "     mu.unique_id ";
    $sql .= "   , mu.other_id ";
    $sql .= "   , mu.last_name ";
    $sql .= "   , mu.first_name ";
    $sql .= "   , mu.last_kana ";
    $sql .= "   , mu.first_kana ";
    $sql .= "   , mu.sex ";
    $sql .= "   , mu.birthday ";
    $sql .= "   , mu.prefecture ";
    $sql .= "   , mu.area ";
    $sql .= "   , mu.address1 ";
    $sql .= "   , mu.address2 ";
    $sql .= "   , mu.address3 ";
    $sql .= "   , mu.post ";
    $sql .= "   , mu.tel1 ";
    $sql .= "   , mu.tel2 ";
    $sql .= "   , mu.fax ";
    $sql .= "   , mu.mail ";
    $sql .= "   , mu.household_type ";
    $sql .= "   , mu.household_memo ";
    $sql .= "   , mu.service_type ";
    $sql .= "   , mu.bath_type ";
    $sql .= "   , mu.bath_memo ";
    $sql .= "   , mu.excretion_type ";
    $sql .= "   , mu.excretion_memo ";
    $sql .= "   , mu.meal_type ";
    $sql .= "   , mu.meal_memo ";
    $sql .= " FROM mst_user mu ";
    $sql .= " WHERE ";
    $sql .= "  mu.delete_flg = 0 ";
    $sql .= " ORDER BY mu.other_id ";

    $res = array();
    return customSQL($sql);
}
