<?php

include 'connection.php';

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:all_posts.php');
}


if (isset($_POST['submit'])) {

    if (isset($_COOKIE['user_id']) && ($_COOKIE['user_id'] != '')) {

        $title = $_POST['title'];
        $title = strip_tags($title);

        $description = $_POST['description'];
        $description = strip_tags($description);

        $rating = $_POST['rating'];

        $verificar_review = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ? AND user_id = ?");
        $verificar_review->bind_param("ii", $get_id, $_COOKIE['user_id']);
        $verificar_review->execute();
        $verificar_review = $verificar_review->get_result();

        if ($verificar_review->num_rows > 0) {
            $warning_msgs = "Your review already added";
        } else {

            $adicionar_review = $conn->prepare("INSERT INTO `reviews` (post_id , user_id , rating , title , description) VALUES (? , ? , ? , ? , ?) ");

            $adicionar_review->bind_param("iisss", $get_id, $_COOKIE['user_id'], $rating, $title, $description);

            $adicionar_review->execute();
            $success_msgs = "Review added";

        }
    } else {
        $warning_msgs = "Please Login First";
    }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <?php
    include 'header.php';
    ?>


    <section class="account-form">

        <form action="" method="post">

            <h3>Post your review</h3>

            <p class="placeholder">Review Title<span>*</span> </p>
            <input type="text" name="title" required maxlength=50 class="box" placeholder="Enter Review Title">

            <p class="placeholder">Review Description</p>
            <textarea name="description" required maxlength=1000 class="box" placeholder="Enter Review description" cols="30" rows="10"> </textarea>

            <p class="placeholder">Review Rating<span>*</span> </p>

            <select name="rating" class="box" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>

            <input type="submit" name="submit" value="Submit Review" class="btn">
            <a href="view_post.php?get_id=<?= $get_id ?>" class="option-btn">Go Back</a>
        </form>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>

    <?php
    include 'alerts.php';
    ?>
</body>

</html>