#!/usr/bin/env php
<?php
set_time_limit(1);

$lines = file('2.txt', FILE_IGNORE_NEW_LINES);

$sample = '7 6 4 2 1
1 2 7 8 9
9 7 6 2 1
1 3 2 4 5
8 6 4 4 1
1 3 6 7 9';
// $lines = explode("\n", $sample);
$match = 0;

foreach ($lines as $line) {
    $nums = explode(' ', $line);
    $res = is_safe($nums);
    if ($res === true) {
        $match++;
        continue;
    }
    continue; // comment out for 2b
    foreach(range($res-1, $res+1) as $i) {
        $test = $nums;
        unset($test[$i]);
        $res = is_safe(array_values($test));
        if ($res === true) {
            $match++;
            continue 2;
        }
    }
}

echo $match;

function is_safe($nums, $second=0) {
    foreach ($nums as $i => $num) {
        if ($i === 0) {
            $dir = $num < $nums[$i+1];
            continue;
        }
        if (($num > $nums[$i-1]) !== $dir) return $i;
        $diff = abs($num - $nums[$i-1]);
        if ($diff < 1 or $diff > 3) return $i;
    }
    return true;
}
