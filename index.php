<?php

use Duffleman\DLN\DLN;

require_once('vendor\autoload.php');

$user1 = [
    'familyName'   => 'Morgan',
    'personalName' => 'Sarah Meredyth',
    'birthDate'    => '1964-07-05',
    'sex'          => 'F',
];

$user2 = [
    'familyName'   => 'Gardner',
    'personalName' => 'Charles',
    'birthDate'    => '1969-05-10',
    'sex'          => 'M',
];

$finalChars = '9IJ';
$completeDLN = 'MORGA657054SM9IJ';

dump(DLN::generate($user1));
dump(DLN::generate($user1, $finalChars));
dump(DLN::generate($user2));
dump(DLN::validate($completeDLN));
dump(DLN::validate($completeDLN, $user1));
dump(DLN::validate($completeDLN, $user2));