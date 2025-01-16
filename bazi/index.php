<?php
include_once("./jdf.php");
session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "bazi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

$days_of_week = ["شنبه", "یک‌شنبه", "دوشنبه", "سه‌شنبه", "چهارشنبه", "پنج‌شنبه"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['city'])) {
        $_SESSION['city'] = $_POST['city'];
    }

    if (isset($_POST['days'])) {
        $_SESSION['days'] = $_POST['days'];

        if (isset($_POST['clear_days'])) {
            unset($_SESSION['days']);
        }
    }

    if (isset($_POST['sort_price'])) {
        $_SESSION['sort_price'] = $_POST['sort'];
    }

    if (isset($_POST['sort_date'])) {
        $_SESSION['sort_date'] = $_POST['sort'];
    }
}

$query = "SELECT * FROM gym WHERE 1=1";
$conditions = [];
$orderByParts = [];

if (!empty($_SESSION['city']) && $_SESSION['city'] !== "") {
    $city = $conn->real_escape_string($_SESSION['city']);
    $conditions[] = "city = '$city'";
}

if (!empty($_SESSION['days']) && is_array($_SESSION['days'])) {
    $days = array_map('intval', $_SESSION['days']);
    $dayPlaceholders = implode(',', $days);
    $conditions[] = "day IN ($dayPlaceholders)";
}

if (!empty($_SESSION['sort_price'])) {
    if ($_SESSION['sort_price'] === "cheap") {
        $orderByParts[] = "price ASC";
    } elseif ($_SESSION['sort_price'] === "expensive") {
        $orderByParts[] = "price DESC";
    }
}

if (!empty($_SESSION['sort_date'])) {
    if ($_SESSION['sort_date'] === "newest") {
        $orderByParts[] = "created_at DESC";
    } elseif ($_SESSION['sort_date'] === "oldest") {
        $orderByParts[] = "created_at ASC";
    }
}

if (!empty($conditions)) {
    $query .= " AND " . implode(" AND ", $conditions);
}

if (!empty($orderByParts)) {
    $query .= " ORDER BY " . implode(", ", $orderByParts);
}

$result = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
</head>

<body>
    <!--سربرگ سایت-->
    <div class="d-flex bg-warning justify-content-between align-items-center">
        <h3 class="text-center text-dark flex-grow-1">دنبال همبازی میگردی؟</h3>
        <h3 class="text-end text-white position-relative">
            همبازی شو
            <span class="text-danger position-absolute fs-6" style="left:-20px;">جدید</span>
        </h3>
    </div>
    <!--منو سایت-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light d-block pt-3 pt-lg-0 shadow">
        <div class="container-fluid justify-content-center justify-content-sm-between">
            <div class="d-flex align-items-center gap-2 mb-3 mb-sm-0">
                <button class="btn btn-primary rounded-pill">ورود مدیران</button>

                <!-- دکمه ورود / عضویت چک میکنه اگه کاربر لاگین کرده باشه دکمه خروج از حساب نمایش بده -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST" action="" style="display:inline; margin:0">
                        <button type="submit" name="logout" class="btn btn-danger rounded-pill">خروج از حساب کاربری</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#authModal">ورود /
                        عضویت</button>
                <?php endif; ?>

                <div dir="rtl" class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="authModalLabel">ورود / عضویت</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- تب‌ها -->
                                <ul class="nav nav-tabs" id="authTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login"
                                            role="tab" aria-controls="login" aria-selected="true">ورود</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#register"
                                            role="tab" aria-controls="register" aria-selected="false">ثبت نام</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="authTabContent">
                                    <!-- فرم ورود -->
                                    <div class="tab-pane fade show active" id="login" role="tabpanel"
                                        aria-labelledby="login-tab">
                                        <form method="POST" action="">
                                            <div class="mb-3">
                                                <label for="loginEmail" class="form-label">ایمیل</label>
                                                <input type="email" name="loginEmail" class="form-control"
                                                    id="loginEmail" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="loginPassword" class="form-label">پسورد</label>
                                                <input type="password" name="loginPassword" class="form-control"
                                                    id="loginPassword" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">ورود</button>
                                        </form>
                                    </div>

                                    <!-- فرم ثبت نام -->
                                    <div class="tab-pane fade" id="register" role="tabpanel"
                                        aria-labelledby="register-tab">
                                        <form method="POST" action="">
                                            <div class="mb-3">
                                                <label for="registerEmail" class="form-label">ایمیل</label>
                                                <input type="email" name="registerEmail" class="form-control"
                                                    id="registerEmail" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="registerPassword" class="form-label">پسورد</label>
                                                <input type="password" name="registerPassword" class="form-control"
                                                    id="registerPassword" required>
                                            </div>
                                            <button type="submit" name="register" class="btn btn-primary">ثبت
                                                نام</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-primary rounded-pill">همکاری باما</button>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center">
                    <li class="nav-item py-2">
                        <a class="nav-link" href="#">بلاگ</a>
                    </li>
                    <li class="nav-item py-2">
                        <a class="nav-link" href="#">فروشنده</a>
                    </li>
                    <li class="nav-item py-2 ">
                        <a class="nav-link" href="hambazi.html">همبازی</a>
                    </li>
                    <!--لیست کشویی-->
                    <li class="nav-item dropdown py-2">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            برنامه های ورزشی
                        </a>
                        <ul class="dropdown-menu text-center">
                            <li><a class="dropdown-item" href="#">برنامه چاقی</a></li>
                            <hr>
                            <li><a class="dropdown-item" href="#">برنامه لاغری</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown py-2">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            مربی های ورزشی
                        </a>
                        <ul class="dropdown-menu text-center">
                            <li><a class="dropdown-item" href="#">فوتبال</a></li>
                            <hr>
                            <li><a class="dropdown-item" href="#">بکس</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown py-2">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            کلاس های ورزشی
                        </a>
                        <ul class="dropdown-menu text-center">
                            <li><a class="dropdown-item" href="#">بکس</a></li>
                            <hr>
                            <li><a class="dropdown-item" href="#">فوتبال</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown py-2">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            مجموعه های ورزشی
                        </a>
                        <ul class="dropdown-menu text-center">
                            <li><a class="dropdown-item" href="#">سالن سرفرازان</a></li>
                            <hr>
                            <li><a class="dropdown-item" href="#">مجموعه تختی</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>

        <div class="container-fluid mt-3">
            <input type="search" class="form-control w-25 d-none d-lg-block" placeholder="جستجو">
            <input type="search" class="form-control w-100 d-block d-lg-none" placeholder="جستجو">
            <ul class="d-flex flex-wrap justify-content-between gap-4 w-100 list-unstyled ms-0 ms-lg-5 mt-3 mt-lg-0">
                <li>
                    <a href="#" class=" text-secondary">کلاس بدن‌سازی</a>
                </li>
                <li>
                    <a href="#" class=" text-secondary">کلاس پیلاتس</a>
                </li>
                <li>
                    <a href="#" class=" text-secondary">کلاس یوگا</a>
                </li>
                <li>
                    <a href="#" class="text-secondary">زمین تنیس</a>
                </li>
                <li>
                    <a href="#" class=" text-secondary">چمن مصنوعی</a>
                </li>
                <li>
                    <a href="#" class=" text-secondary">سالن فوتسال</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid my-2">
        <div class="row">
            <div class="col-12 col-lg-9 order-1 order-lg-0">
                <h4 class="text-center my-3">مشاهده بر روی نقشه</h4>
                <form method="POST" action="" dir="rtl">
                    <select name="sort" id="sort" class="form-select">
                        <option value="">انتخاب بر اساس قیمت</option>
                        <option value="cheap" <?php echo (isset($_SESSION['sort_price']) && $_SESSION['sort_price'] === 'cheap') ? 'selected' : ''; ?>>ارزان‌ترین</option>
                        <option value="expensive" <?php echo (isset($_SESSION['sort_price']) && $_SESSION['sort_price'] === 'expensive') ? 'selected' : ''; ?>>گران‌ترین</option>
                    </select>
                    <button type="submit" name="sort_price" class="btn btn-primary my-3">مرتب‌سازی قیمت</button>
                </form>

                <div class="row gy-2">
                    <!-- نمایش سالن های ورزشی -->
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="col-12 col-md-4">
                                <div class="shadow d-flex flex-column h-100">
                                    <img src="./salon 1.jpg" class="w-100 h-100" alt="salon">
                                    <div class="d-flex justify-content-around align-items-center pt-3 pb-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <h6>: قیمت از</h6>
                                            <h6><?php echo htmlspecialchars($row['price']); ?> تومان</h6>
                                            <?php
                                            $timestamp = strtotime($row['created_at']);
                                            $createdAt = jdate('Y/m/d', $timestamp);
                                            ?>
                                            <span class="text-warning">تاریخ: <?php echo $createdAt; ?></span>
                                        </div>
                                        <div class="d-none d-lg-flex" style="height: 80px;">
                                            <div class="vr"></div>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <h6><?php echo htmlspecialchars($row['city']); ?></h6>
                                            <h6><?php echo htmlspecialchars($row['name']); ?></h6>
                                            <h6><?php echo htmlspecialchars($row['address']); ?></h6>
                                            <span>روز های : <?php echo htmlspecialchars($days_of_week[$row['day']]); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>هیچ پستی موجود نیست.</p>
                    <?php endif; ?>

                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="accordion">
                    <div class="accordion-item shadow">
                        <h2 class="accordion-header py-2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                بر اساس تاریخ
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <form method="POST">
                                    <div class="accordion-body">
                                        <div class="form-check d-flex flex-row-reverse mb-2">
                                            <input class="form-check-input ms-2" type="radio" name="sort" value="newest"
                                                id="newest" <?php echo (isset($_SESSION['sort_date']) && $_SESSION['sort_date'] === 'newest') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="newest">جدیدترین</label>
                                        </div>
                                        <div class="form-check d-flex flex-row-reverse mb-2">
                                            <input class="form-check-input ms-2" type="radio" name="sort" value="oldest"
                                                id="oldest" <?php echo (isset($_SESSION['sort_date']) && $_SESSION['sort_date'] === 'oldest') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="oldest">قدیمی‌ترین</label>
                                        </div>
                                        <button type="submit" name="sort_date"
                                            class="btn btn-primary mt-3">فیلتر</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="form-check form-switch d-flex justify-content-between align-items-center shadow py-4 px-3 rounded-3">
                    <input class="form-check-input ms-0" type="checkbox" id="flexSwitchCheckDefault">
                    <label class="form-check-label fw-bold" for="flexSwitchCheckDefault">قابلیت رزرو آنلاین</label>
                </div>
                <div class="accordion">
                    <div class="accordion-item shadow mb-1">
                        <h2 class="accordion-header py-2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                شهر ها
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <form method="POST" action="">
                                    <label for="city" class="form-label">انتخاب شهر:</label>
                                    <select name="city" id="city" class="form-select">
                                        <option value="">همه شهرها</option>
                                        <option value="مشهد" <?php echo (isset($_SESSION['city']) && $_SESSION['city'] === 'مشهد') ? 'selected' : ''; ?>>مشهد</option>
                                        <option value="تهران" <?php echo (isset($_SESSION['city']) && $_SESSION['city'] === 'تهران') ? 'selected' : ''; ?>>تهران</option>
                                        <option value="تبریز" <?php echo (isset($_SESSION['city']) && $_SESSION['city'] === 'تبریز') ? 'selected' : ''; ?>>تبریز</option>
                                        <option value="شیراز" <?php echo (isset($_SESSION['city']) && $_SESSION['city'] === 'شیراز') ? 'selected' : ''; ?>>شیراز</option>
                                    </select>
                                    <button type="submit" name="filter_city" class="btn btn-primary mt-3">اعمال فیلتر
                                        شهر</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow">
                        <h2 class="accordion-header py-2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                جستجوی سانس خالی در هفته جاری
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse show"
                            data-bs-parent="#accordionExample">
                            <form method="POST">
                                <div class="accordion-body">
                                    <div class="d-flex w-100 justify-content-between mb-3">
                                        <div class="form-check d-flex flex-row-reverse">
                                            <input class="form-check-input ms-2" type="checkbox" name="days[]" value="4"
                                                <?php echo (isset($_SESSION['days']) && in_array(4, $_SESSION['days'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label">چهارشنبه</label>
                                        </div>
                                        <div class="form-check d-flex flex-row-reverse">
                                            <input class="form-check-input ms-2" type="checkbox" name="days[]" value="0"
                                                <?php echo (isset($_SESSION['days']) && in_array(0, $_SESSION['days'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label">شنبه</label>
                                        </div>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between mb-3">
                                        <div class="form-check d-flex flex-row-reverse">
                                            <input class="form-check-input ms-2" type="checkbox" name="days[]" value="5"
                                                <?php echo (isset($_SESSION['days']) && in_array(5, $_SESSION['days'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label">پنج‌شنبه</label>
                                        </div>
                                        <div class="form-check d-flex flex-row-reverse">
                                            <input class="form-check-input ms-2" type="checkbox" name="days[]" value="1"
                                                <?php echo (isset($_SESSION['days']) && in_array(1, $_SESSION['days'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label">یک‌شنبه</label>
                                        </div>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between mb-3">
                                        <div class="form-check d-flex flex-row-reverse">
                                            <input class="form-check-input ms-2" type="checkbox" name="days[]" value="6"
                                                <?php echo (isset($_SESSION['days']) && in_array(6, $_SESSION['days'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label">جمعه</label>
                                        </div>
                                        <div class="form-check d-flex flex-row-reverse">
                                            <input class="form-check-input ms-2" type="checkbox" name="days[]" value="2"
                                                <?php echo (isset($_SESSION['days']) && in_array(2, $_SESSION['days'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label">دوشنبه</label>
                                        </div>
                                    </div>
                                    <div class="d-flex w-100 justify-content-end">
                                        <div class="form-check d-flex flex-row-reverse">
                                            <input class="form-check-input ms-2" type="checkbox" name="days[]" value="3"
                                                <?php echo (isset($_SESSION['days']) && in_array(3, $_SESSION['days'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label">سه‌شنبه</label>
                                        </div>
                                    </div>
                                    <button type="submit" name="filter_days" class="btn btn-primary mt-3">فیلتر</button>
                                    <button type="submit" name="clear_days" class="btn btn-danger mt-3">حذف
                                        فیلتر</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./bootstrap.bundle.min.js"></script>
</body>

</html>