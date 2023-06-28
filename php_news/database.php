<?php

$conn = new PDO('mysql:host=localhost;dbname=news2022', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$conn->query('SET NAMES utf8');

