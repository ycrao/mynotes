<?php
/**
 * 拼手气红包
 *
 * @param int $totalBonus 红包总金额 <为了精度问题，这里使用分为单位>
 * @param int $bonusSize 红包份数
 * @return array
 */
function randBonus($totalBonus, $bonusSize) {
    if ($totalBonus < 1) {
        die('红包总金额不得少于1分钱').PHP_EOL;
    }
    if ($bonusSize < 1) {
        die('红包份数不得少于1份').PHP_EOL;
    }
    if ($totalBonus < $bonusSize) {
        die('红包金额不足，不满足拼手气红包要求！').PHP_EOL;
    }
    $remainBonus = $totalBonus;  // 剩余可分配的红包金额
    $iCount = 0;
    $divisions = [];
    $min = 1;  // 随机最小值使用 1
    while ($iCount < $bonusSize) {
        if ($iCount < $bonusSize - 1) {
            $restSize = $bonusSize - $iCount;
            $max = bcdiv($remainBonus, $restSize)*2;  // 随机最大值使用 未分配金额的平均值的2倍
            $randCent = $min;
            if (($remainBonus > $restSize*$min) && $max > $min ) {
                $randCent = mt_rand($min, $max);   // 随机金额范围
            }
            $divisions[] = $randCent;
            $remainBonus -= $randCent;
            echo 'max:'.$max.',iCount:'.$iCount.',randCent:'.$randCent.',remainBonus:'.$remainBonus.PHP_EOL;
        } else {
            $divisions[] = $remainBonus;
            echo 'max:null,iCount:'.$iCount.',randCent:'.$remainBonus.',remainBonus:0'.PHP_EOL;
        }
        $iCount ++;
    }
    return $divisions;
}
$totalBonus = 16;
$bonusSize = 10;
// 0.16 元 10 人份
$ret = randBonus($totalBonus, $bonusSize);
$total = array_sum($ret);
echo '红包总金额：'.$total.' 分,'.'红包总份数：'.$bonusSize.' 份'.PHP_EOL;
echo '拼手气红包领取明细：'.implode(',', $ret).PHP_EOL;