<?php

include 'connection.php';

setcookie('user_id', "", 0, '/');

header('location:all_posts.php');
