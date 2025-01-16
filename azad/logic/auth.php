<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['login'] = true;
        echo "<script>
            window.location.href = window.location.href; // بارگذاری مجدد صفحه
        </script>";
    } else {
        echo "<script>alert('ایمیل یا رمز عبور نادرست است.')</script>";
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    echo "<script>
        window.location.href = window.location.href; // بارگذاری مجدد صفحه
    </script>";
    exit();
}
?>