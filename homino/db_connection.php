<?php

// اطلاعات اتصال به پایگاه داده
// این ip همون localhost هستش
$servername = "127.0.0.1";
// یوزرنیم دیتابیس به طور پیشفرض مقدار root هستش
$username = "root"; 
// دیتابیس پسورد نداره
$password = "";
// اسم دیتابیس
$dbname = "homino";

// ایجاد اتصال به پایگاه داده
$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی خطای اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>