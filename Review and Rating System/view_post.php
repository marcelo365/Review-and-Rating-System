<?php

include 'connection.php';

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:all_posts.php');
}



if (isset($_POST['delete_review'])) {

    $delete_id = $_POST['delete_id'];

    $delete_review = $conn->prepare("DELETE FROM `reviews` WHERE id = ?");
    $delete_review->bind_param("i", $delete_id);
    $delete_review->execute();

    $success_msgs = "Review deleted !";
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

    <section class="view-post">

        <div class="heading">
            <h1>Detalhes do Post</h1>
            <a href="all_posts.php" class="inline-option-btn" style="margin-top:0;">All Posts</a>
        </div>

        <?php
        $select_post = $conn->prepare("SELECT * FROM `posts` WHERE id = ? LIMIT 1 ");
        $select_post->bind_param("i", $get_id);
        $select_post->execute();
        $select_post = $select_post->get_result();

        if ($select_post->num_rows > 0) {

            $res = $select_post->fetch_assoc();

            $total_ratings = 0; //somatorio de todas as ratings
            $rating_1 = 0; //somatorio de todas as ratings com pontuação 1
            $rating_2 = 0; //somatorio de todas as ratings com pontuação 2
            $rating_3 = 0; //somatorio de todas as ratings com pontuação 3
            $rating_4 = 0; //somatorio de todas as ratings com pontuação 4
            $rating_5 = 0; //somatorio de todas as ratings com pontuação 5

            $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ? ");
            $select_reviews->bind_param("i", $res['id']);
            $select_reviews->execute();
            $select_reviews = $select_reviews->get_result();

            $total_reviews = $select_reviews->num_rows;

            while ($review = $select_reviews->fetch_assoc()) {

                $total_ratings += $review['rating'];

                if ($review['rating'] == 1) {
                    $rating_1 += $review['rating'];
                } else if ($review['rating'] == 2) {
                    $rating_2 += $review['rating'];
                } else if ($review['rating'] == 3) {
                    $rating_3 += $review['rating'];
                } else if ($review['rating'] == 4) {
                    $rating_4 += $review['rating'];
                } else if ($review['rating'] == 5) {
                    $rating_5 += $review['rating'];
                }
            }

            if ($total_reviews != 0) {
                $average = round($total_ratings / $total_reviews, 1);
            } else {
                $average = 0;
            }
        ?>

            <div class="row">

                <div class="col">
                    <img src="uploaded_images/<?= $res['image'] ?>" alt="" class="image">
                    <h3 class="title"><?= $res['title'] ?></h3>
                </div>

                <div class="col">
                    <div class="flex">

                        <div class="total-reviews">
                            <h3><?= $average ?> <i class="fa-solid fa-star"> </i></h3>
                            <p><?= $total_reviews ?> reviews</p>
                        </div>

                        <div class="total-ratings">

                            <p>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <span><?= $rating_5 ?></span>
                            </p>

                            <p>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <span><?= $rating_4 ?></span>
                            </p>


                            <p>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <span><?= $rating_3 ?></span>
                            </p>


                            <p>
                                <i class="fa-solid fa-star"> </i>
                                <i class="fa-solid fa-star"> </i>
                                <span><?= $rating_2 ?></span>
                            </p>


                            <p>
                                <i class="fa-solid fa-star"> </i>
                                <span><?= $rating_1 ?></span>
                            </p>

                        </div>

                    </div>

                </div>

            </div>


        <?php
        } else {
            echo '<p class="empty">Post is missing</p>';
        }
        ?>
    </section>





    <section class="reviews-container">

        <div class="heading">
            <h1>User's reviews</h1>
            <a href="add_review.php?get_id=<?= $get_id ?>" class="inline-btn" style="margin-top: 0;">Add Review</a>
        </div>


        <div class="box-container">

            <?php
            $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
            $select_reviews->bind_param("i", $get_id);
            $select_reviews->execute();
            $select_reviews = $select_reviews->get_result();

            if ($select_reviews->num_rows > 0) {

                while ($review = $select_reviews->fetch_assoc()) {
            ?>

                    <div class="box"
                        <?php
                        if (isset($_COOKIE['user_id']) && ($review['user_id'] == $_COOKIE['user_id'])) {
                            echo 'style="order: -1"';
                        }
                        ?>>

                        <?php
                        $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
                        $select_user->bind_param("i", $review['user_id']);
                        $select_user->execute();
                        $select_user = $select_user->get_result();
                        $select_user = $select_user->fetch_assoc();
                        ?>

                        <div class="user">

                            <?php
                            if ($select_user['image'] != '') {
                            ?>
                                <img src="uploaded_images/<?= $select_user['image'] ?>" alt="">

                            <?php
                            } else {  ?>
                                <h3> <?= substr($select_user['name'], 0, 1) ?> </h3>
                            <?php
                            }
                            ?>

                            <div>
                                <p><?= $select_user['name'] ?></p>
                                <span><?= $review['date'] ?></span>
                            </div>
                        </div>

                        <div class="ratings">

                            <?php
                            if ($review['rating'] == 1) {
                            ?>
                                <p style="background: var(--red);"> <i class="fa-solid fa-star"> </i> <span><?= $review['rating'] ?></span> </p>
                            <?php  } ?>


                            <?php
                            if ($review['rating'] == 2) {
                            ?>
                                <p style="background: var(--orange);"> <i class="fa-solid fa-star"> </i> <span><?= $review['rating'] ?></span> </p>
                            <?php  } ?>


                            <?php
                            if ($review['rating'] == 3) {
                            ?>
                                <p style="background: var(--orange);"> <i class="fa-solid fa-star"> </i> <span><?= $review['rating'] ?></span> </p>
                            <?php  } ?>


                            <?php
                            if ($review['rating'] == 4) {
                            ?>
                                <p style="background: var(--main-color);"> <i class="fa-solid fa-star"> </i> <span><?= $review['rating'] ?></span> </p>
                            <?php  } ?>


                            <?php
                            if ($review['rating'] == 5) {
                            ?>
                                <p style="background: var(--main-color);"> <i class="fa-solid fa-star"> </i> <span><?= $review['rating'] ?></span> </p>
                            <?php  } ?>
                        </div>

                        <h3 class="title"><?= $review['title'] ?></h3>
                        <?php
                        if ($review['description'] != '') {  ?>
                            <p class="description"><?= $review['description'] ?></p>
                        <?php  } ?>


                        <?php
                        if (isset($_COOKIE['user_id']) && ($review['user_id'] == $_COOKIE['user_id'])) {
                        ?>

                            <form action="" method="post" class="flex-btn">

                                <input type="hidden" name="delete_id" value="<?= $review['id'] ?>">

                                <a href="update_review.php?get_id=<?= $review['id'] ?>" class="inline-option-btn">Edit Review</a>

                                <input type="submit" value="delete review" class="inline-delete-btn" name="delete_review" onclick="return confirm( 'delete this review ?' )">

                            </form>

                        <?php } ?>

                    </div>


            <?php
                }
            } else {
                echo '<p class="empty">No reviews added yet</p>';
            }
            ?>


        </div>


    </section>



    <?php
    include 'alerts.php';
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
</body>

</html>