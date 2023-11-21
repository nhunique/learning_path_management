<?php

$db_user="root";
$db_pass ="";
$db_name ="learningPaths_DB";

$db = new PDO('mysql:host=localhost;port=4306;dbname=' . $db_name . ';charset=utf8', $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

