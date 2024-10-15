<?php

function getMstUser($keyId, $isDetail = false, $target = '*', $orderBy = 'unique_id DESC')
{
    $where = array();
    $where['unique_id']  = $keyId;
    $where['delete_flg'] = 0;
    return select('mst_user', $target, $where, $orderBy);
}

function getContractBusinessHistory($keyId, $isDetail = false, $target = '*', $orderBy = 'unique_id DESC')
{
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    if (!$isDetail){
        $where['start_day <='] = TODAY;
        $where['end_day >']   = TODAY;
    }
    return select('mst_user_office1', '*', $target, $where, $orderBy);
}

function getHomeSupportOffices($keyId, $isDetail = false, $target = '*', $orderBy = 'unique_id DESC')
{
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    if (!$isDetail){
        $target  = 'unique_id, user_id, start_day, office_code';
        $target .= ', office_name, address, tel, fax, found_day';
        $target .= ', person_name, person_kana, plan_type';
    }
    return select('mst_user_office2', $target, $where, $orderBy);
}

function getMstUserPay($keyId, $isDetail = false, $target = '*', $orderBy = 'unique_id DESC')
{
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    if (!$isDetail){
        $target  = 'unique_id, user_id, method, bank_type';
        $target .= ', bank_code, bank_name, branch_code, branch_name';
        $target .= ', deposit_type, deposit_code, deposit_name';
    }
    return select('mst_user_pay', $target, $where, $orderBy);
}
