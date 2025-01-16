<?php

include("./db_connection.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $email = $conn->real_escape_string($_POST['registerEmail']);
    $password = password_hash($_POST['registerPassword'], PASSWORD_BCRYPT);

    $checkQuery = "SELECT id FROM user WHERE email = '$email'";
    $checkResult = $conn->query($checkQuery);
    if ($checkResult->num_rows > 0) {
        echo "<script>alert('این ایمیل قبلاً ثبت شده است.');</script>";
    } else {
        $insertQuery = "INSERT INTO user (email, password) VALUES ('$email', '$password')";
        if ($conn->query($insertQuery)) {
            echo "<script>
                alert('ثبت‌نام با موفقیت انجام شد.');
                window.location.href = window.location.href;
            </script>";
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginEmail'])) {
    $email = $conn->real_escape_string($_POST['loginEmail']);
    $password = $_POST['loginPassword'];

    $query = "SELECT id, password FROM user WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            echo "<script>
                alert('ورود با موفقیت انجام شد.');
                window.location.href = window.location.href;
            </script>";
            exit;
        } else {
            echo "<script>alert('رمز عبور اشتباه است.');</script>";
        }
    } else {
        echo "<script>alert('کاربری با این ایمیل یافت نشد.');</script>";
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
}

?>