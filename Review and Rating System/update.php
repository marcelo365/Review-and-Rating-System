<?php

include 'connection.php';


if (isset($_POST['submit'])) {

    $user_seleccionado = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
    $user_seleccionado->bind_param("i", $_COOKIE['user_id']);
    $user_seleccionado->execute();
    $user_seleccionado = $user_seleccionado->get_result();
    $user_seleccionado = $user_seleccionado->fetch_assoc();

    $name = $_POST['name'];
    $name = strip_tags($name);

    $email = $_POST['email'];
    $email = strip_tags($email);

    if (!empty($name)) {
        $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ? ");
        $update_name->bind_param("si", $name, $_COOKIE['user_id']);
        $update_name->execute();
        $success_msgs = "Username Atualizado";
    }

    if (!empty($email)) {

        $verificar_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $verificar_email->bind_param("s", $email);
        $verificar_email->execute();

        $verificar_email = $verificar_email->get_result();

        if ($verificar_email->num_rows > 0) {
            $warning_msgs = "Email já está ser usado";
        } else {
            $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ? ");
            $update_email->bind_param("si", $email, $_COOKIE['user_id']);
            $update_email->execute();
            $success_msgs = "Email Atualizado";
        }
    }

    //imagem
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_name = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_type = $_FILES['image']['type'];

    $upload_dir = 'uploaded_images/';  //destino onde as imagens irão (nesse caso a pasta)
    $dest_path = $upload_dir . $image_name;

    if (!empty($image_name)) {

        if ($image_size > 2000000) { //se a imagem for maior que 2MB
            $warning_msgs = 'tamanho de imagem maior que 2mb';
        } else {
            move_uploaded_file($image_tmp_name, $dest_path);
            //

            if ($user_seleccionado['image'] != "") {
                unlink('uploaded_images/' . $user_seleccionado['image']);
            }

            $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ? ");
            $update_image->bind_param("si", $image_name, $_COOKIE['user_id']);
            $update_image->execute();
            $success_msgs = "Imagem Atualizada";
        }
    }

    // password

    $password = $_POST['password'];
    $password = strip_tags($password);
    $password = password_hash($password, PASSWORD_DEFAULT); //pega a password do input e gera um hash para essa password que será guardado na BD

    $new_password = $_POST['new_password'];
    $new_password = strip_tags($new_password);
    $new_password = password_hash($new_password, PASSWORD_DEFAULT); //pega a password do input e gera um hash para essa password que será guardado na BD

    $empty_password = password_verify("", $password);
    $empty_new_password = password_verify("", $new_password);


    $c_new_password = $_POST['c_new_password'];
    $c_new_password = strip_tags($c_new_password);
    $c_new_password = password_verify($c_new_password, $new_password);

    if (!$empty_password) {
        $verificar_password = password_verify(strip_tags($_POST['password']), $user_seleccionado['password']);

        if ($verificar_password) {

            if ($c_new_password) {
                if (!$empty_new_password) {

                    $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ? ");
                    $update_password->bind_param("si", $new_password, $_COOKIE['user_id']);
                    $update_password->execute();
                    $success_msgs = "Password Atualizada";
                } else {
                    $warning_msgs = "Porfavor introduza uma nova password";
                }
            } else {
                $warning_msgs = "Password não confirmada correctamente";
            }
        } else {
            $warning_msgs = "Palavra passe antiga não corresponde";
        }
    }
}

if (isset($_POST['delete_image'])) {

    $user_seleccionado = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
    $user_seleccionado->bind_param("i", $_COOKIE['user_id']);
    $user_seleccionado->execute();
    $user_seleccionado = $user_seleccionado->get_result();
    $user_seleccionado = $user_seleccionado->fetch_assoc();

    if ($user_seleccionado['image'] == '') {
        $warning_msgs = "Sem imagem para apagar";
    } else {

        $empty = "";

        if ($user_seleccionado['image'] != '') {
            unlink('uploaded_images/' . $user_seleccionado['image']);
        }

        $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ? ");
        $update_image->bind_param("si", $empty, $_COOKIE['user_id']);
        $update_image->execute();

        $success_msgs = "Imagem Apagada";
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
            <h3>Atualização de perfil</h3>

            <p class="placeholder">Seu Nome <span>*</span> </p>
            <input type="text" name="name" maxlength=50 class="box" placeholder="<?= $perfil_seleccionado['name'] ?>">

            <p class="placeholder">Seu Email <span>*</span> </p>
            <input type="text" name="email" maxlength=50 class="box" placeholder="<?= $perfil_seleccionado['email'] ?>">

            <p class="placeholder">Sua Password <span>*</span> </p>
            <input type="password" name="password" maxlength=50 class="box" placeholder="Insira a sua password">

            <p class="placeholder">Nova Password <span>*</span> </p>
            <input type="password" name="new_password" maxlength=50 class="box" placeholder="Insira a sua nova password">

            <p class="placeholder">Confirme a nova Password <span>*</span> </p>
            <input type="password" name="c_new_password" maxlength=50 class="box" placeholder="Confirme a sua nova password">

            <p class="placeholder">Foto de Perfil <span>*</span> </p>

            <?php
            if ($perfil_seleccionado['image'] != "") {
            ?>
                <img src="uploaded_images/<?= $perfil_seleccionado['image'] ?>" class="image">
                <input type="submit" name="delete_image" value="delete image" class="delete-btn">
            <?php } ?>

            <input type="file" name="image" class="box" accept="image/*">


            <input type="submit" name="submit" value="Atualizar Perfil" class="btn">

        </form>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php
    include 'alerts.php';
    ?>

</body>

</html>