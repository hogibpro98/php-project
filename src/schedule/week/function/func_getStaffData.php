<?php
/* =============================================================================
 * 従業員データ取得関数（基本情報）
 * =============================================================================
 */
function getStaffDataStd()
{
    $res = array();
    $staffMst = array();
    $temp = select('mst_staff', '*');
    foreach ($temp as $val) {
        $tgtId    = $val['unique_id'];
        $tgtStaffId = $val['staff_id'];
        $tgtLastName = $val['last_name'];
        $tgtFirstName = $val['first_name'];
        $tgtLastKana = $val['last_kana'];
        $tgtFirstKana = $val['first_kana'];
        $tgtBirthday = $val['birthday'];
        $tgtSex = $val['sex'];
        $tgtAddress = $val['address'];
        $tgtTel = $val['tel'];
        $tgtEmgContact = $val['emg_contact'];
        $tgtMail = $val['mail'];
        $tgtRole1 = $val['role1'];
        $tgtRole2 = $val['role2'];
        $tgtLinkageName = $val['linkage_name'];
        $tgtLinkageCode = $val['linkage_code'];
        $tgtLicense1 = $val['license1'];
        $tgtJob = $val['job'];
        $tgtLicense2 = $val['license2'];
        $tgtRetired = $val['retired'];
        $tgtRemarks = $val['remarks'];
        $tgtEmgName = $val['emg_name'];
        $tgtEmgKana = $val['emg_kana'];
        $tgtRelationType = $val['relation_type'];
        $tgtEmgAddress = $val['emg_address'];
        $tgtEmgMail = $val['emg_mail'];
        $tgtEmgTel = $val['emg_tel'];
        $tgtEmgPhone = $val['emg_phone'];
        $tgtEmgRemarks = $val['emg_remarks'];
        $tgtAccount = $val['account'];
        $tgtPassword = $val['password'];
        $tgtType = $val['type'];
        $tgtEmployeeType = $val['employee_type'];
        $tgtDrivingLicense = $val['driving_license'];

        $staffMst[$tgtId] = $tgtId;
        $staffMst[$tgtId] = $tgtStaffId;
        $staffMst[$tgtId] = $tgtLastName;
        $staffMst[$tgtId] = $tgtFirstName;
        $staffMst[$tgtId] = $tgtLastKana;
        $staffMst[$tgtId] = $tgtFirstKana;
        $staffMst[$tgtId] = $tgtBirthday;
        $staffMst[$tgtId] = $tgtSex;
        $staffMst[$tgtId] = $tgtAddress;
        $staffMst[$tgtId] = $tgtTel;
        $staffMst[$tgtId] = $tgtEmgContact;
        $staffMst[$tgtId] = $tgtMail;
        $staffMst[$tgtId] = $tgtRole1;
        $staffMst[$tgtId] = $tgtRole2;
        $staffMst[$tgtId] = $tgtLinkageName;
        $staffMst[$tgtId] = $tgtLinkageCode;
        $staffMst[$tgtId] = $tgtLicense1;
        $staffMst[$tgtId] = $tgtJob;
        $staffMst[$tgtId] = $tgtLicense2;
        $staffMst[$tgtId] = $tgtRetired;
        $staffMst[$tgtId] = $tgtRemarks;
        $staffMst[$tgtId] = $tgtEmgName;
        $staffMst[$tgtId] = $tgtEmgKana;
        $staffMst[$tgtId] = $tgtRelationType;
        $staffMst[$tgtId] = $tgtEmgAddress;
        $staffMst[$tgtId] = $tgtEmgMail;
        $staffMst[$tgtId] = $tgtEmgTel;
        $staffMst[$tgtId] = $tgtEmgPhone;
        $staffMst[$tgtId] = $tgtEmgRemarks;
        $staffMst[$tgtId] = $tgtAccount;
        $staffMst[$tgtId] = $tgtPassword;
        $staffMst[$tgtId] = $tgtType;
        $staffMst[$tgtId] = $tgtEmployeeType;
        $staffMst[$tgtId] = $tgtDrivingLicense;
    }
    return $staffMst;
}

/* =============================================================================
 * 従業員データ取得関数(基本情報＋所属情報)
 * =============================================================================
 */
function getStaffDataDtl()
{
    $res = array();
    $condition = array();
    $joinconditions = array();

    // ToDp : Join句の指定方法
    $joinconditions = "mst_staff.staff_id = mst_staff_office.staff_id";
    $temp = getMultiData('mst_staff', 'mst_staff_office', '*');
    foreach ($temp as $val) {
        $tgtId    = $val['unique_id'];
        $tgtStaffId = $val['staff_id'];
        $tgtLastName = $val['last_name'];
        $tgtFirstName = $val['first_name'];
        $tgtLastKana = $val['last_kana'];
        $tgtFirstKana = $val['first_kana'];
        $tgtBirthday = $val['birthday'];
        $tgtSex = $val['sex'];
        $tgtAddress = $val['address'];
        $tgtTel = $val['tel'];
        $tgtEmgContact = $val['emg_contact'];
        $tgtMail = $val['mail'];
        $tgtRole1 = $val['role1'];
        $tgtRole2 = $val['role2'];
        $tgtLinkageName = $val['linkage_name'];
        $tgtLinkageCode = $val['linkage_code'];
        $tgtLicense1 = $val['license1'];
        $tgtJob = $val['job'];
        $tgtLicense2 = $val['license2'];
        $tgtRetired = $val['retired'];
        $tgtRemarks = $val['remarks'];
        $tgtEmgName = $val['emg_name'];
        $tgtEmgKana = $val['emg_kana'];
        $tgtRelationType = $val['relation_type'];
        $tgtEmgAddress = $val['emg_address'];
        $tgtEmgMail = $val['emg_mail'];
        $tgtEmgTel = $val['emg_tel'];
        $tgtEmgPhone = $val['emg_phone'];
        $tgtEmgRemarks = $val['emg_remarks'];
        $tgtAccount = $val['account'];
        $tgtPassword = $val['password'];
        $tgtType = $val['type'];
        $tgtEmployeeType = $val['employee_type'];
        $tgtDrivingLicense = $val['driving_license'];

        $staffMst[$tgtId] = $tgtId;
        $staffMst[$tgtId] = $tgtStaffId;
        $staffMst[$tgtId] = $tgtLastName;
        $staffMst[$tgtId] = $tgtFirstName;
        $staffMst[$tgtId] = $tgtLastKana;
        $staffMst[$tgtId] = $tgtFirstKana;
        $staffMst[$tgtId] = $tgtBirthday;
        $staffMst[$tgtId] = $tgtSex;
        $staffMst[$tgtId] = $tgtAddress;
        $staffMst[$tgtId] = $tgtTel;
        $staffMst[$tgtId] = $tgtEmgContact;
        $staffMst[$tgtId] = $tgtMail;
        $staffMst[$tgtId] = $tgtRole1;
        $staffMst[$tgtId] = $tgtRole2;
        $staffMst[$tgtId] = $tgtLinkageName;
        $staffMst[$tgtId] = $tgtLinkageCode;
        $staffMst[$tgtId] = $tgtLicense1;
        $staffMst[$tgtId] = $tgtJob;
        $staffMst[$tgtId] = $tgtLicense2;
        $staffMst[$tgtId] = $tgtRetired;
        $staffMst[$tgtId] = $tgtRemarks;
        $staffMst[$tgtId] = $tgtEmgName;
        $staffMst[$tgtId] = $tgtEmgKana;
        $staffMst[$tgtId] = $tgtRelationType;
        $staffMst[$tgtId] = $tgtEmgAddress;
        $staffMst[$tgtId] = $tgtEmgMail;
        $staffMst[$tgtId] = $tgtEmgTel;
        $staffMst[$tgtId] = $tgtEmgPhone;
        $staffMst[$tgtId] = $tgtEmgRemarks;
        $staffMst[$tgtId] = $tgtAccount;
        $staffMst[$tgtId] = $tgtPassword;
        $staffMst[$tgtId] = $tgtType;
        $staffMst[$tgtId] = $tgtEmployeeType;
        $staffMst[$tgtId] = $tgtDrivingLicense;
    }
    return $staffMst;
}
