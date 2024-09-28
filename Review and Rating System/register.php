<?php

include 'connection.php';
//$error_msgs = "Jjjj";

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = strip_tags($name);


    $email = $_POST['email'];
    $email = strip_tags($email);

    $password = $_POST['password'];
    $password = strip_tags($password);
    $password = password_hash($password, PASSWORD_DEFAULT); //pega a password do input e gera um hash para essa password que será guardado na BD

    $c_password = $_POST['c_password'];
    $c_password = strip_tags($c_password);
    $c_password = password_verify($c_password, $password);

    //imagem
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_name = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_type = $_FILES['image']['type'];

    $upload_dir = 'uploaded_images/';  //destino onde as imagens irão (nesse caso a pasta)
    $dest_path = $upload_dir . $image_name;

    if ($image_size > 2000000) { //se a imagem for maior que 2MB
        $warning_msgs = 'tamanho de imagem maior que 2mb';
    } else {
        move_uploaded_file($image_tmp_name, $dest_path);

        //

        $verificar_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $verificar_email->bind_param("s", $email);
        $verificar_email->execute();

        $verificar_email = $verificar_email->get_result();

        if ($verificar_email->num_rows > 0) {
            $warning_msgs = "Email já está ser usado digite outro porfavor";
        } else {

            if ($c_password) {

                $inserir_usuario = $conn->prepare("INSERT INTO `users` (name , email , password , image) VALUES (? , ? , ? , ?) ");

                $inserir_usuario->bind_param("ssss", $name, $email, $password, $image_name);

                $inserir_usuario->execute();

                $success_msgs = "Usuário registado com sucesso";
            } else {
                $warning_msgs = "A senha confirmada não combina com a senha digitada";
            }
        }
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

        <form action="" method="post" enctype="multipart/form-data"> <!--enctype sempre que tivermos um input do tipo file -->
            <h3>Crie a sua conta !</h3>

            <p class="placeholder">Seu Nome <span>*</span> </p>
            <input type="text" name="name" required maxlength=50 class="box" placeholder="Insira o seu nome">

            <p class="placeholder">Seu Email <span>*</span> </p>
            <input type="text" name="email" required maxlength=50 class="box" placeholder="Insira o seu email">

            <p class="placeholder">Sua Password <span>*</span> </p>
            <input type="password" name="password" required maxlength=50 class="box" placeholder="Insira a sua password">

            <p class="placeholder">Confirme a sua password <span>*</span> </p>
            <input type="password" name="c_password" required maxlength=50 class="box" placeholder="Confirme a sua password">

            <p class="placeholder">Escolha uma imagem <span>*</span> </p>
            <input type="file" name="image" class="box" accept="image/*">

            <p class="link">Já possui uma conta? <a href="login.php">faça login</a></p>

            <input type="submit" name="submit" value="registre-se agora" class="btn">

        </form>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php
    include 'alerts.php';
    ?>

</body>

</html>