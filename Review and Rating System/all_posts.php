<?php

include 'connection.php';


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

    <section class="all-posts">

        <div class="heading">
            <h1>all posts</h1>
        </div>

        <div class="box-container">

            <?php
            $posts_seleccionados = $conn->prepare("SELECT * FROM `posts`");
            $posts_seleccionados->execute();
            $posts_seleccionados = $posts_seleccionados->get_result();

            if ($posts_seleccionados->num_rows > 0) {
                while ($post = $posts_seleccionados->fetch_assoc()) {


                    $count_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
                    $count_reviews->bind_param("i", $post['id']);
                    $count_reviews->execute();
                    $count_reviews = $count_reviews->get_result();
                    $total_reviews = $count_reviews->num_rows;
            ?>
                    <div class="box">
                        <img src="uploaded_images/<?= $post['image'] ?>" alt="" class="image">
                        <h3 class="title"><?= $post['title'] ?> </h3>
                        <p class="total-reviews"> <i class="fa-solid fa-star"> </i>
                            <span> <?= $total_reviews ?></span>
                        </p>
                        <a href="view_post.php?get_id= <?=$post['id']?>" class="inline-btn">Ver post</a>
                    </div>
            <?php
                }
            } else {
                echo ' <p class="empty">Nenhum post disponível</p>';
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