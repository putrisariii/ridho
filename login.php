<?php

session_start();

include 'conf/app.php';

// check apakah tombol login ditekan
if (isset($_POST['login'])) {
    // ambil input username dan password
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // check username
    $result = mysqli_query($conn, "SELECT * FROM akun WHERE username = '$username'");

    // jika ada username 
    if (mysqli_num_rows($result) == 1) {
        // check password
        $hasil = mysqli_fetch_assoc($result);

        if (password_verify($password, $hasil['password'])) {
            // set session
            $_SESSION['login'] = true;
            $_SESSION['id_akun'] = $hasil['id_akun'];
            $_SESSION['nama'] = $hasil['nama'];

            // jika login benar akan diarahkan ke index.php
            header("Location: home_admin.php");
            exit;
        }
    }
    // jika tidak ada usernya/login salah
    $error = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="login.css">
    <link href='icon/fontawesome/css/all.min.css' rel='stylesheet'>
    
</head>
<body>
     <!-- Navbar -->
     <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav" style="text-align: left">
            <div class="container px-4 px-lg-5">
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive"> 
                    <ul class="navbar-nav ms-auto my-2 my-lg-0">
                        <!--<li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house-chimney" style="color: white"></i> Home</a></li>-->
                    </ul>
                </div>
            </div>
        </nav>
    <!--End Navbar -->

     <!--Form Login -->

   <div class="box">
    <div class="container">

    <form action="" method="post">
        <div class="top-header">
            <!--<span>Have an account?</span>-->
            <header>Login</header>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert text-white bg-danger">
                <a>Wrong Username or password!</a>
            </div>
            <?php endif;?>
        <div class="input-field">
            <input type="text" class="input" placeholder="Username" id="" name="username" required>
            <i class="fa-solid fa-user"></i>
        </div>

        <div class="input-field">
            <input type="Password" class="input" placeholder="Password" id="" name="password" required>
            <i class="fa-solid fa-key"></i>
        </div>

        <div class="input-field">
            <input type="submit" class="submit" value="Login" name="login">
        </div>
        </form>
    </div>
</div>  
</body>
<script src="bootstrap/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</html>
