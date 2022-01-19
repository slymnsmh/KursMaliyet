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