<header class="header">

    <section class="flex">
        <a href="all_posts.php" class="logo">Logo.</a>

        <nav class="navbar">
            <a href="all_posts.php"><i class="fa-regular fa-eye"></i></a>
            <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i></a>
            <a href="register.php"><i class="fa-regular fa-registered"></i></a>

            <?php
            // Verifica se o cookie 'user_id' existe e não está vazio
            if (isset($_COOKIE['user_id']) && $_COOKIE['user_id'] != "") {
                echo '<a><i class="fa-regular fa-user" id="user-btn" onclick="mostrarPerfil()"></i></a>';
            }
            ?>

            <?php
            if (isset($_COOKIE['user_id']) && $_COOKIE['user_id'] != "") {
            ?>

                <div class="profile">
                    <?php
                    $perfil_seleccionado = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
                    $perfil_seleccionado->bind_param("i", $_COOKIE['user_id']);
                    $perfil_seleccionado->execute();
                    $perfil_seleccionado = $perfil_seleccionado->get_result();

                    if ($perfil_seleccionado->num_rows > 0) {
                        $perfil_seleccionado = $perfil_seleccionado->fetch_assoc();
                    ?>
                        <p> <?= $perfil_seleccionado['name']; ?></p>

                        <?php
                        if ($perfil_seleccionado['image'] != "") { ?>
                        <img src="uploaded_images/<?=$perfil_seleccionado['image']?>" alt="" class="image">
                        <?php }
                        ?>

                        <a href="update.php" class="btn">Atualizar Perfil</a>
                        <a href="logout.php" class="delete-btn" onclick="return confirm('sair desse perfil ?')">Sair</a>

                    <?php } else { ?>
                        <div class="flex-btn">
                            <p>Porfavor faça login na sua conta ou registe-se</p>
                            <a href="login.php" class="inline-option-btn">Login</a>
                            <a href="register.php" class="inline-option-btn">Registrar</a>
                        </div>
                    <?php } ?>
                </div>


            <?php }; ?>


        </nav>
    </section>

</header>