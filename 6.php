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
// $data = trim(file_get_contents('6.txt'));

// first index is the row, second column, so (y,x)
$grid = array_map('str_split', explode("\n", $data));

$height = count($grid);
$width = count($grid[0]);

$pos = strpos($data, '^');
$x = $pos % ($width + 1);
$y = floor($pos / ($width + 1));
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

move($x, $y, $dir);

function move($x, $y, $dir, $check_loop=false) {
    global $width, $height, $dirs, $new_dir, $moves, $grid;
    
    while($x >= 0 and $x < $width and $y >= 0 and $y < $height) {
        $new_y = $y + $dirs[0][$dir];
        $new_x = $x + $dirs[1][$dir];
        if (! isset($grid[$new_y][$new_x])) {
            echo "moves: $moves\ny, x: $y, $x\n";
            return;
        }
        $char_at = $grid[$new_y][$new_x];
        if (in_array($char_at, ['.', 'X'])) {
            $y = $new_y;
            $x = $new_x;
            $grid[$new_y][$new_x] = 'X';
            if ($char_at == '.') $moves++;
        }elseif ($grid[$new_y][$new_x] === '#') {
            $dir = $new_dir[$dir];
        }else {
            echo "moves: $moves\ny, x: $y, $x\n";
            return;
        }
    }
}
