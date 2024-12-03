#!/usr/bin/env php
<?php
set_time_limit(1);

$data = file_get_contents('3.txt');

// $data = 'xmul(2,4)%&mul[3,7]!@^do_not_mul(5,5)+mul(32,64]then(mul(11,8)mul(8,5))';
// $data = "xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))";
preg_match_all('/(mul\(\d+,\d+\)|do\(\)|don\'t\(\))/m', $data, $matches);

$add = true;
$result = 0;
foreach ($matches[1] as $instruction) {
    $type = substr($instruction, 0, 3);
    if ($add) {
        if ($type == 'mul') {
            list($a, $b) = explode(',', substr($instruction, 4, -1));
            $result += $a * $b;
        }elseif ($type == 'don') {
            $add = false;
        }
    }else{
        if ($type == 'do(') {
            $add = true;
        }
    }
}
echo $result;
