<?php

function readCsv($filename){
    $first = true;
    $ret = array();
    $titles = array();

    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($first){
                $titles = $data;
                $first = !$first;
            }
            else {
                $rowArray = array();
                for ($c=0; $c < count($data); $c++) {
                    $rowArray[$titles[$c]] = is_numeric($data[$c]) ? floatval($data[$c]) : $data[$c];
                }
                $ret[] = $rowArray;
            }
        }
        fclose($handle);
        return $ret;
    }
    else {
        throw new Exception("Could not open $filename");
    }
}


function getAgeInYears($date){
    return floor((time() - strtotime($date)) / (60 * 60 * 24 * 365));
}

function calculateCopay($plan, $familyMembers) {
    $totalFromOfficeVisits = 0;
    foreach($familyMembers as $familyMember){
        $totalFromOfficeVisits += $familyMember['Office Visits'] * $plan['Copay'];
    }
    return $totalFromOfficeVisits;
}

function calculatePremium($plan, $familyMembers) {
    $premium = 0;
    if ($plan['Premium Type'] && $plan['Premium Type'] == 'age'){
        foreach($familyMembers as $familyMember){
            $ageInYears = getAgeInYears($familyMember['DOB']);
            if ($ageInYears < 21){
                $ageInYears = 'under21';
            }

            if (!$plan['P_' . $ageInYears]){
                throw new Exception("Missing premium amount for plan {$plan['Plan']} and age $ageInYears");
            }
            $premium += $plan['P_' . $ageInYears];
        }
    }
    else {
        $premium = $plan['Total Premium'];
    }

    return $premium;
}

function calculateExtra($plan, $familyMembers) {
    $extra = 0;
    foreach($familyMembers as $familyMember){
        if ($familyMember['Extra']){
            if ($familyMember['Extra'] > $plan['Deductible']){
                $deductible = $plan['Deductible'];
                $amountAfterDeducitble = $familyMember['Extra'] - $deductible;
                $coins = 100-$plan['Coinsurance'];

                if ($coins > 0){
                    $amountLeftToPay = (($coins/100) * $amountAfterDeducitble);
                    $extra += min($amountLeftToPay + $deductible, $plan['OPM']);
                }
                else {
                    $extra += $deductible;
                }
            }
            else {
                $extra += $familyMember['Extra'];
            }
        }
    }

    return $extra;
}

function writeTitle($plan){
    printf("%-12s %-15s %-15s %-15s %-15s\n", 'Plan', 'Total Premium', 'Total Extra', 'Total Copay', 'Total');
}

function writePlan($plan){
    printf("%-12s $%-15.2f $%-15.2f $%-15.2f $%-15.2f\n", $plan['Plan'], $plan['Total Premium'], $plan['Total Extra'], $plan['Total Copay'], $plan['Total']);
}