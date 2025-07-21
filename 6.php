<?php

$data = "....#.....
.........#
..........
..#.......
.......#..
..........
.#..^.....
........#.
#.........
......#...";

// .##..
// oo.o#
// #..#.
// .^#..

$xdata = ".##..
....#
#..#.
.^#..";

$xdata = "...........#.....#......
...................#....
...#.....##.............
......................#.
..................#.....
..#.....................
....................#...
........................
.#........^.............
..........#..........#..
..#.....#..........#....
........#.....#..#......";


$data = trim(file_get_contents('6.txt'));

// first index is the row, second column, so (y,x)
$grid = array_map('str_split', explode("\n", $data));

$height = count($grid);
$width = count($grid[0]);

$pos = strpos($data, '^');
$x = $pos % ($width + 1);
$y = (int) floor($pos / ($width + 1));
echo "Starting y, x: $y, $x\n";
$dir = 'up';
$dirs = [
    ['up'=>-1, 'left'=>0, 'right'=>0, 'down'=>1],
    ['up'=>0, 'left'=>-1, 'right'=>1, 'down'=>0],
];
$new_dir = [
    'up'=>'right',
    'right'=>'down',
    'down'=>'left',
    'left'=>'up',
];

$grid[$y][$x] = '.';

$moves = 0;
$obstacles = [];

move($x, $y, $dir);
// obstacles is now a filled array with (y, x) pairs but may contain duplicate values. array_unique only works on 1-dim arrays so squash them together
$obstacles = array_unique(array_map(fn($a)=>$a[0].':'.$a[1], $obstacles));

echo "Obstacles: ". count($obstacles)."\n";


/*
B: At each step, if the path ahead is clear, check if placing an obstacle would result in a loop.
   Detect a loop by >placing a temp obstacle< in the next cell and taking a right and following that route until you come to a cell where you've moved in that direction
     on that location before.
   A location can have multiple previous directions.
   These previous direction/location matches can be from the history of the initial path -or- from the current loop-checking path.
   Also, temp obstacles can not be placed in locations which were already visited, since that uh.. changes history *nods*.

*/

function move($x, $y, $dir, $check_loop=false, $potential=null) {
    global $width, $height, $dirs, $new_dir, $moves, $grid, $history, $obstacles;
    $local_history = [];
    while($x >= 0 and $x < $width and $y >= 0 and $y < $height) {
        if ($check_loop) {
            if (! empty($history[$y][$x][$dir]) or ! empty($local_history[$y][$x][$dir])) {
                // been here before
                $obstacles[] = $potential;
                return;
            }
            $local_history[$y][$x][$dir] = 1;
        }else{
            $history[$y][$x][$dir] = 1;
        }
        $new_y = $y + $dirs[0][$dir];
        $new_x = $x + $dirs[1][$dir];
        if (! isset($grid[$new_y][$new_x])) {
            return;
        }
        if ($check_loop and $potential[0] == $new_y and $potential[1] == $new_x) {
            $char_at = '#';
        }else {
            $char_at = $grid[$new_y][$new_x];
        }
        if (in_array($char_at, ['.', 'X'])) {
            if (! $check_loop and $char_at == '.') {
                $history[$y][$x][$dir] = 1;
                move($x, $y, $new_dir[$dir], true, [$new_y, $new_x]); // recurse
            // }else{
            //     $local_history[$y][$x][$dir] = 1;
            }
            $y = $new_y;
            $x = $new_x;
            if (! $check_loop) $grid[$new_y][$new_x] = 'X';
            if ($char_at === '.') $moves++;
        }elseif ($char_at === '#') {
            $dir = $new_dir[$dir];
        }
        // draw_grid($x, $y);
    }
}

function draw_grid($X, $Y) {
    global $grid, $history, $obstacles, $moves;
    echo "\e[2J";
    foreach ($grid as $y => $row) {
        foreach ($row as $x => $cell) {
            if ($x==$X and $y==$Y) echo "\e[31;1m$cell\e[0m";
            else echo $cell;
        }
        echo "\n";
    }
    echo "Moves: $moves, Obstacles: ".count($obstacles)."\n";
    // usleep(100000);
}
