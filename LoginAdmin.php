<?php
session_start();

if(isset($_SESSION['status']) && $_SESSION['status'] == "login"){
    header("Location: Admin.php"); 
    exit;
}

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == "masukbayar" && $password == "cobatanya"){
        $_SESSION['username'] = $username;
        $_SESSION['status'] = "login";
        
        // Simpan pesan berhasil di session
        $_SESSION['login_success'] = "Login Berhasil! Selamat Datang Admin.";
        
        header("Location: Admin.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Cling Laundry</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h2>Cling<span>.</span></h2>
            <p>Silakan login untuk mengelola laundry.</p>
        </div>

        <?php if(isset($error)) { ?>
            <div style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error; ?></div>
        <?php } ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="login" class="btn-login" style="width: 100%; padding: 10px; cursor: pointer;">Login Admin</button>
        </form>
    </div>

</body>
</html>