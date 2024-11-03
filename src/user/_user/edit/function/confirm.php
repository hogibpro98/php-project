<?php

//=====================================================================
// 入力中判定
//=====================================================================

// NG判定対象外設定
$ngAry = getNgAry();

if ($upAry) {
    $tgtAry = $dispData['standard'];
    foreach ($upAry as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upPay) {
    $tgtAry = $dispData['pay'];
    foreach ($upPay as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upOfc1) {
    $tgtAry = $dispData['office1'];
    foreach ($upOfc1 as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upOfc2) {
    $tgtAry = $dispData['office2'];
    foreach ($upOfc2 as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upIns1) {
    $tgtAry = $dispData['insure1'];
    foreach ($upIns1 as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upIns2) {
    $tgtAry = $dispData['insure2'];
    foreach ($upIns2 as $seq => $upAry2) {
        $tgtAry = $dispData['insure2'];
        foreach ($upAry2 as $key => $val) {
            if (isset($ngAry[$key])) {
                continue;
            }
            if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
                $entryFlg = true;
                break;
            }
        }
    }
}
if (!$entryFlg && $upIns3) {
    $tgtAry = $dispData['insure3'];
    foreach ($upIns3 as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upIns4) {
    $tgtAry = $dispData['insure4'];
    foreach ($upIns4 as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upMdc) {
    $tgtAry = $dispData['medical'];
    foreach ($upMdc as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upHsp) {
    $tgtAry = $dispData['hospital'];
    foreach ($upHsp as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upSvc) {
    $tgtAry = $dispData['service'];
    foreach ($upSvc as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upInt) {
    $tgtAry = $dispData['introduct'];
    foreach ($upInt as $key => $val) {
        if (isset($ngAry[$key])) {
            continue;
        }
        if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
            $entryFlg = true;
            break;
        }
    }
}
if (!$entryFlg && $upImg) {
    foreach ($upImg as $seq => $upAry2) {
        $tgtAry = $dispData['image'];
        foreach ($upAry2 as $key => $val) {
            if (isset($ngAry[$key])) {
                continue;
            }
            if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
                $entryFlg = true;
                break;
            }
        }
    }
}
if (!$entryFlg && $upDrg) {
    foreach ($upDrg as $seq => $upAry2) {
        $tgtAry = $dispData['drug'];
        foreach ($upAry2 as $key => $val) {
            if (isset($ngAry[$key])) {
                continue;
            }
            if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
                $entryFlg = true;
                break;
            }
        }
    }
}
if (!$entryFlg && $upFml) {
    foreach ($upFml as $seq => $upAry2) {
        $tgtAry = $dispData['family'];
        foreach ($upAry2 as $key => $val) {
            if (isset($ngAry[$key])) {
                continue;
            }
            if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
                $entryFlg = true;
                break;
            }
        }
    }
}
if (!$entryFlg && $upEmg) {
    foreach ($upEmg as $seq => $upAry2) {
        $tgtAry = $dispData['emergency'];
        foreach ($upAry2 as $key => $val) {
            if (isset($ngAry[$key])) {
                continue;
            }
            if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
                $entryFlg = true;
                break;
            }
        }
    }
}
if (!$entryFlg && $upPsn) {
    foreach ($upPsn as $seq => $upAry2) {
        $tgtAry = $dispData['person'];
        foreach ($upAry2 as $key => $val) {
            if (isset($ngAry[$key])) {
                continue;
            }
            if ($val && isset($tgtAry[$key]) && ($val != $tgtAry[$key])) {
                $entryFlg = true;
                break;
            }
        }
    }
}
