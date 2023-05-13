<?php
## Incluir archivo de conexión
include 'components/connect.php';

## Al enviar el formulario
if (isset($_POST['submit'])) {

    $id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $c_pass = password_verify($_POST['c_pass'], $pass);
    $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/' . $rename;

    ## Tamaño maximo de la imagen 
    if (!empty($image)) {
        if ($image_size > 2000000) {
            $warning_msg[] = 'Image size is too large!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $rename = '';
    }

    ## Verificar Email si existe en la base de datos.
    $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $verify_email->execute([$email]);

    ## Enviar alerta si existe
    if ($verify_email->rowCount() > 0) {
        $warning_msg[] = 'Email already taken!';
    } else {
        ## Verificar que el valor que realiza la validación sea TRUE (1)
        if ($c_pass == 1) {
            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $pass, $rename]);
            $success_msg[] = 'Registered successfully!';
        } else {
            ## Enviar alerta que la contraseña no es igual
            $warning_msg[] = 'Confirm password not matched!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Header section starts -->
    <header>
        <?php include "components/header.php"; ?>
    </header>
    <!-- Header section ends -->


    <section class="account-form">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>make your account!</h3>
            <p class="placeholder">your name <span>*</span></p>
            <input type="text" name="name" require maxlength="50" placeholder="enter your name" class="box">

            <p class="placeholder">your email <span>*</span></p>
            <input type="email" name="email" require maxlength="50" placeholder="enter your email" class="box">

            <p class="placeholder">your password <span>*</span></p>
            <input type="password" name="pass" require maxlength="50" placeholder="enter your password" class="box">

            <p class="placeholder">confirm password <span>*</span></p>
            <input type="password" name="c_pass" require maxlength="50" placeholder="confirm your password" class="box">

            <p class="placeholder">profile pic</p>
            <input type="file" name="image" class="box" accept="image/*">

            <p class="link">already have an account? <a href="login.php">login now</a></p>
            <input type="submit" value="register now" name="submit" class="btn">
        </form>
    </section>


    <!-- sweetaler cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js file link -->
    <script src="js/script.js"></script>

    <?php include "components/alers.php" ?>
</body>

</html>