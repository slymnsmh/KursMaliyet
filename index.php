<?php
    include("conn.php");

    $info = "";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $user_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
        
        if (mysqli_num_rows($user_query) == 0) {
            $info = "Yanlış kullanıcı bilgileri!";
        } else {
            $user = mysqli_fetch_array($user_query);

            session_start();
            $_SESSION['user_id'] = $user["id"];
            header("location: user.php");
        }
    }

    if (isset($_GET['from']) && $_GET['from'] == "userPage") {
        $info = "Giriş yapmadan kullanıcı sayfasına erişemezsiniz!";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Giriş Yap</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="login">
            <h1 style="font-size: 4vw;">Maliyet Hesaplama Programı</h1>
            <h2 style="font-size: 5vw;">Giriş Yap</h2>
            <form method="post" style="display: flex; flex-direction: column;">
                <div style="display: flex; flex-direction: row; width: 100%; justify-content: center; height: 15vw;">
                    <label for="username" style="width: 30%; height: 80%;">
                        <i class="fas fa-user fa-5x"></i>
                    </label>
                    <input style="width: 60%; height: 80%; font-size: 5vw;" type="text" name="username" placeholder="Kullanıcı Adı" id="username" required>
                </div>
                <div style="display: flex; flex-direction: row; width: 100%; justify-content: center; height: 15vw;">
                    <label for="password" style="width: 30%; height: 80%;">
                        <i class="fas fa-lock fa-5x"></i>
                    </label>
                    <input style="width: 60%; height: 80%; font-size: 5vw;" type="password" name="password" placeholder="Şifre" id="password" required>
                </div>
                <?php echo $info; ?>
                <input style="width: 100%; height: 80%; font-size: 5vw;" type="submit" value="Login" name="submit">
            </form>
        </div>
    </body>
</html>