<?php

echo "Hello from the docker yooooo container";

$mysqli = new mysqli("db", "root", "password", "company_1");

$sql_1 = 'INSERT INTO `users` (username) VALUES ("LIL SNEAZY")';
$mysqli->query($sql_1);

$sql = 'SELECT * FROM `users`';
$users = $mysqli->query($sql);
$data = $users->fetch_all();

var_dump($data);
if ($mysqli->query($sql)) {
    echo "everything is all right";
} else {
    echo "there was an error during inserting";
}


/*
$sql = 'SELECT * FROM users';

if ($result = $mysqli->query($sql)) {
    while ($data = $result->fetch_object()) {
        $users[] = $data;
    }
}

foreach ($users as $user) {
    echo "<br>";
    echo $user->name . " " . $user->fav_color;
    echo "<br>";
}
*/