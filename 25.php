<?php

set_time_limit(5);

$data = "#####
.####
.####
.####
.#.#.
.#...
.....

#####
##.##
.#.##
...##
...#.
...#.
.....

.....
#....
#....
#...#
#.#.#
#.###
#####

.....
.....
#.#..
###..
###.#
###.#
#####

.....
.....
.....
#....
#.#..
#.#.#
#####";

$data = trim(file_get_contents('25.txt'));

// split key and lock strings
foreach(explode("\n\n", $data) as $item)  ${substr($item, 0, 5) == '.....' ? 'keys' : 'locks'}[] = $item;
$keys = array_map('numberize', $keys);      
$locks = array_map('numberize', $locks);

// var_export($locks);
$match = 0;
foreach($locks as $i => $lock) {
    foreach($keys as $j => $key) {
        foreach($lock as $p => $pin) {
            if ($pin + $key[$p] > 5) continue 2;
        }
        $match++;
    }
}
echo $match;

// -- functions --------------------------

function transpose($array) {
    return array_map(null, ...$array);
}

function numberize($str) {
    return array_map(fn($a) => substr_count($a, '#'), array_map('join', (transpose(array_map('str_split', explode("\n", substr($str, 6, -6)))))));
}
