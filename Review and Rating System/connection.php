<?php

$host_name = "localhost";
$username = "root";
$password = "";
$database = "reviews_db";
$port = 3377;

$conn = new mysqli($host_name, $username, $password, $database, $port);
//localhost , nome do usuario , senha , nome da base de dados

if ($conn->connect_error) {
    echo "aaaaaaaa";
    die("connection failed : " . $conn->connect_error);
} else {
    //echo "boaaaa";
}
