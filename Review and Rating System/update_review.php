<?php

include 'connection.php';


if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:all_posts.php');
}

if (isset($_POST['submit'])) {

    $title = $_POST['title'];
    $title = strip_tags($title);

    $description = $_POST['description'];
    $description = strip_tags($description);

    $rating = $_POST['rating'];

    $update_review = $conn->prepare("UPDATE `reviews` SET rating = ? , title = ? , description = ? WHERE id = ?");

    $update_review->bind_param("issi" , $rating , $title , $description , $get_id);

    $update_review->execute();

    $success_msgs = "Review Updated !";
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

        <?php

        $select_review = $conn->prepare("SELECT * FROM `reviews` WHERE id = ? LIMIT 1");
        $select_review->bind_param("i", $get_id);
        $select_review->execute();
        $select_review = $select_review->get_result();

        if ($select_review->num_rows > 0) {

            $select_review = $select_review->fetch_assoc();

        ?>

            <form action="" method="post">

                <h3>Edit your review</h3>

                <p class="placeholder">Review Title<span>*</span> </p>
                <input type="text" name="title" required maxlength=50 class="box" placeholder="Enter Review Title" value="<?= $select_review['title'] ?>">

                <p class="placeholder">Review Description</p>
                <textarea name="description" required maxlength=1000 class="box" placeholder="Enter Review description" cols="30" rows="10"> <?= $select_review['description'] ?> </textarea>

                <p class="placeholder">Review Rating<span>*</span> </p>

                <select name="rating" class="box" required>
                    <option value="<?= $select_review['rating'] ?>"><?= $select_review['rating'] ?></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <input type="submit" name="submit" value="Update Review" class="btn">
                <a href="view_post.php?get_id=<?= $select_review['post_id'] ?>" class="option-btn">Go Back</a>
            </form>

        <?php

        } else {
            echo '<p class="empty">Something went wrong</p>';
        }
        ?>

    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php
    include 'alerts.php';
    ?>

</body>

</html>