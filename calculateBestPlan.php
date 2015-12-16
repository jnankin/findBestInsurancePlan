<?

require('insuranceLogic.php');

$plans = readCsv($argv[1]);
$familyMembers = readCsv($argv[2]);

writeTitle();
foreach($plans as &$plan){
    $plan['Total Premium'] = calculatePremium($plan, $familyMembers);
    $plan['Total Extra'] = calculateExtra($plan, $familyMembers);
    $plan['Total Copay'] = calculateCopay($plan, $familyMembers);
    $plan['Total'] = ($plan['Total Premium'] * 12) + $plan['Total Extra'] + $plan['Total Copay'];
}

function cmp($a, $b)
{
    if ($a['Total'] == $b['Total']) {
        return 0;
    }
    return ($a['Total'] < $b['Total']) ? -1 : 1;
}

usort($plans, "cmp");

foreach($plans as $plan){
    writePlan($plan);
}