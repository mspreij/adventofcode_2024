#!/usr/bin/env php
<?php

$lines = file('1.txt', FILE_IGNORE_NEW_LINES);

$sample = '3   4
4   3
2   5
1   3
3   9
3   3';
//$lines = explode("\n", $sample);

foreach($lines as $line) list($list1[], $list2[]) = explode('   ', $line);
sort($list1);
sort($list2);

$answer = 0;

// 1a
// foreach($list1 as $i => $one) $answer += abs($one - $list2[$i]);

// 1b
$freq = array_count_values($list2);
foreach($list1 as $val) if (isset($freq[$val])) $answer += $val * $freq[$val];

echo $answer;
