#!/usr/bin/env php
<?php
set_time_limit(1);

$data = file_get_contents('3.txt');

//$data = 'xmul(2,4)%&mul[3,7]!@^do_not_mul(5,5)+mul(32,64]then(mul(11,8)mul(8,5))';
preg_match_all('/(mul\(\d+,\d+\))/m', $data, $matches);

$result = 0;
foreach ($matches[1] as $instruction) {
    list($a, $b) = explode(',', substr($instruction, 4, -1));
    $result += $a * $b;
}
echo $result;
