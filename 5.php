#!/usr/bin/env php
<?php
set_time_limit(5);

$data = file_get_contents('5.txt');

$datxa = '47|53
97|13
97|61
97|47
75|29
61|13
75|53
29|13
97|29
53|29
61|53
97|53
61|29
47|13
75|47
97|75
47|61
75|61
47|29
75|13
53|13

75,47,61,53,29
97,61,53,29,13
75,29,13
75,97,47,61,53
61,13,29
97,13,75,29,47';

[$orders_original, $updates] = array_map(fn($part) => explode("\n", trim($part)), explode("\n\n", $data));
$orders = $orders_original;

foreach($updates as $update) {
    $res = is_valid($update);
    if (is_array($res)) {
        $invalid_updates[] = $res;
    }
}

// print_r($invalid_updates);
$valid_updates = [];

foreach ($invalid_updates as $update) {
    usort($update, function($a, $b) {
        global $orders;
        $pair = "$a|$b";
        if (in_array($pair, $orders)) {
            return 1;
        }elseif(in_array("$b|$a", $orders)) {
            return -1;
        }
        return 0;
    });
    $valid_updates[] = $update;
}

// print_r($valid_updates);
$sum = 0;
foreach($valid_updates as $variable_naming_hard) {
    $sum += $variable_naming_hard[abs(count($variable_naming_hard)/2)];
}
echo $sum;

function is_valid($update) {
    global $orders;
    $items = explode(",", $update);
    foreach ($items as $i => $item) {
        if (! isset($items[$i+1])) break;
        $next = $items[$i+1];
        if (! is_numeric(array_search("$item|$next", $orders))) {
            return $items;
        }
    }
    return true;
}


die("\nx.x\n");

// my cunning plan is to loop through the order items and turn them into an ordered list, by repeatedly finding the values which only appear in the first half of all orders.
// they obviously come before every other value. those orders are then removed, and then find the next first-half-only value etc.
// when explaining this to ChatGPT looking for a single-pass way it was like "hey that's Kahn's algorithm". why am I always too late?
// Update: well THAT DIDN'T WORK
$first = '';
$i = 0;
while ($i<1000) {
    if (count($orders) === 1) {
        // this must be the last loop, so the second half is the last item
        $ordered[] = explode('|', current($orders))[1];
        break; // and done!
    }
    $first_items = $last_items = [];
    foreach($orders as $i => $order) {
        [$a, $b] = explode('|', $order);
        // if $first has a value, it comes from the previous while-loop, and we can toss the pairs starting with it.
        if ($a == $first) { // not triple but double '=' because the numerical strings become integers when you use them as array indices? or something?
            unset($orders[$i]);
            continue;
        }
        $last_items[$a] = $first_items[$b] = false; // these things we know.
        if (! isset($first_items[$a])) $first_items[$a] = true;
        if (! isset($last_items[$b]))  $last_items[$b] = true; // d'ya like DAGs? (yes I just learned what that actually means)
    }
    // now $first_items should only contain a single true item
    $first = key(array_filter($first_items));
    $ordered[] = $first;
    $i++;
}


if ($i > 1000) die(" booh :-(");

foreach($updates as $update) {
    $items = explode(",", $update);
    foreach ($items as $i => $item) {
        if (! isset($items[$i+1])) break;
        if (array_search($item, $ordered) > array_search($items[$i+1], $ordered)) {
            continue 2;
        }
    }
    $valid_updates[] = $items;
}

$sum = 0;
foreach($valid_updates as $variable_naming_hard) {
    $sum += $variable_naming_hard[abs(count($variable_naming_hard)/2)];
}
echo $sum;
