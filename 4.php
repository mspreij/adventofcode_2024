#!/usr/bin/env php
<?php
set_time_limit(1);

$lines = file('4.txt', FILE_IGNORE_NEW_LINES);

$sample = 'MMMSXXMASM
MSAMXMSMSA
AMXSXMAAMM
MSAMASMSMX
XMASAMXAMM
XXAMMXXAMA
SMSMSASXSS
SAXAMASAAA
MAMMMXMMMM
MXMXAXMASX';
// $lines = explode("\n", $sample);
$line_count = count($lines);

error_reporting(0); // "nothing to see here, move along"

// part A
$len = strlen($lines[0]);
$matches = 0;
foreach($lines as $y => $line) {
    for($x=0; $x<$len; $x++) {
        if ($line[$x] != 'X') continue;
        if (substr($line, $x, 4) == 'XMAS') $matches++; // would they were all this easy
        if (substr($line, $x-3, 4) == 'SAMX') $matches++;
        if ($y >= 3) {
            if ($x >= 3) if ($lines[$y-3][$x-3].$lines[$y-2][$x-2].$lines[$y-1][$x-1] == 'SAM') $matches++; // up-left
            if ($lines[$y-3][$x].$lines[$y-2][$x].$lines[$y-1][$x] == 'SAM') $matches++; // up
            if ($x <= $len - 3) if ($lines[$y-3][$x+3].$lines[$y-2][$x+2].$lines[$y-1][$x+1] == 'SAM') $matches++; // up-right
        }
        if ($y <= $line_count - 3) {
            if ($x >= 3) if ($lines[$y+3][$x-3].$lines[$y+2][$x-2].$lines[$y+1][$x-1] == 'SAM') $matches++; // bottom-left
            if ($lines[$y+3][$x].$lines[$y+2][$x].$lines[$y+1][$x] == 'SAM') $matches++; // down
            if ($x <= $len - 3) if ($lines[$y+3][$x+3].$lines[$y+2][$x+2].$lines[$y+1][$x+1] == 'SAM') $matches++; // bottom-right
        }
    }
}
echo "A: $matches\n";

// part B (AKA "oh come the **** on..")
$matches = 0;
foreach($lines as $y => $line) {
    for($x=0; $x<$len; $x++) {
        if ($line[$x] != 'A' or $x == 0 or $y ==0 or $x == $line_count or $y == $len) continue; // ignore border A's
        if (in_array($lines[$y-1][$x-1].$lines[$y][$x].$lines[$y+1][$x+1], ['SAM', 'MAS'])) {
            if (in_array($lines[$y-1][$x+1].$lines[$y][$x].$lines[$y+1][$x-1], ['SAM', 'MAS'])) {
                $matches++;
            }
        }
    }
}
echo "B: $matches\n";
