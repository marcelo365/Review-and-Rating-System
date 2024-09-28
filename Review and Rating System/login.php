<?php

include 'connection.php';

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $email = strip_tags($email);

    $password = $_POST['password'];
    $password = strip_tags($password);

    $verificar_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
    $verificar_email->bind_param("s", $email);
    $verificar_email->execute();

    $verificar_email = $verificar_email->get_result();

    if ($verificar_email->num_rows > 0) {
        $linha = $verificar_email->fetch_assoc();
        $verificar_password = password_verify($password, $linha['password']);

        if ($verificar_password) {
            setcookie('user_id', $linha['id'], 0, '/'); //o cookie serve para armazenar um valor
            $success_msgs = "Login feito com sucesso";
            header('location:all_posts.php');
        } else {
            $error_msgs = "Password incorrecta";
        }
    } else {
        $error_msgs = "Email não encontrado";
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

        <form action="" method="post"> <!--enctype sempre que tivermos um input do tipo file -->
            <h3>Login</h3>

            <p class="placeholder">Seu Email <span>*</span> </p>
            <input type="text" name="email" required maxlength=50 class="box" placeholder="Insira o seu email">

            <p class="placeholder">Sua Password <span>*</span> </p>
            <input type="password" name="password" required maxlength=50 class="box" placeholder="Insira a sua password">

            <p class="link">Ainda não possui uma conta? <a href="register.php">registre-se</a></p>

            <input type="submit" name="submit" value="Entrar" class="btn">

        </form>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php
    include 'alerts.php';
    ?>

</body>

</html>