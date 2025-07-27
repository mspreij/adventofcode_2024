<?php

$data = "
292: 11 6 16 20
190: 10 19
3267: 81 40 27
83: 17 5
156: 15 6
7290: 6 8 6 15
161011: 16 10 13
192: 17 8 14
21037: 9 7 18 13
";
// 3749

$data = trim(file_get_contents('7.txt'));

$lines = array_map(fn($a) => explode(" ", str_replace(':', '', $a)), explode("\n", trim($data)));
$sum = 0;

foreach ($lines as $l => $line) {
    $answer = array_shift($line);
    $operator_count = count($line)-1;
    $attempts = 2**$operator_count;
    for ($attempt=0; $attempt < $attempts; $attempt++) { 
        $operators = str_pad((string) decbin($attempt), $operator_count, 0, STR_PAD_LEFT    ); // turn attempt-number into zeros and ones that'll translate into + and * or something
        $test = $line[0];
        $debug = $test;
 //       echo "operators: $operators\n";
        for ($o=0; $o < $operator_count; $o++) { 
            $operator = $operators[$o]; // 0/1
          //  echo "op: $operator\n";
            switch ($operator) {
                case 0:
                    $test = $test + $line[$o+1];
                    $debug .= ' + '. $line[$o+1];
                    break;
                case 1:
                    $test = $test * $line[$o+1];
                    $debug .= ' * '. $line[$o+1];
                    break;
            }
        }
        // echo "$debug\n";
        if ($test == $answer) {
            // echo "------------------------ answer $test\n";
            $sum += $test;
            break;
        }

    }
    
}
echo $sum;
