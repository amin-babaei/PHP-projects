<?php

include("./register.php");

$minPrice = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
$maxPrice = isset($_GET['max_price']) ? intval($_GET['max_price']) : 1000000;
$meterFilters = isset($_GET['meter']) ? $_GET['meter'] : [];

$priceCondition = "price BETWEEN $minPrice AND $maxPrice";


$meterCondition = "";
if (!empty($meterFilters)) {
    $meterClauses = [];
    foreach ($meterFilters as $filter) {
        if ($filter === "50") {
            $meterClauses[] = "meter < 50";
        } elseif ($filter === "50-80") {
            $meterClauses[] = "(meter >= 50 AND meter <= 80)";
        } elseif ($filter === "100") {
            $meterClauses[] = "meter > 100";
        }
    }
    $meterCondition = " AND (" . implode(" OR ", $meterClauses) . ")";
}

$whereClause = $priceCondition . $meterCondition;

$sql = "SELECT title, price, meter, id FROM product WHERE $whereClause";
$result = $conn->query($sql);

if (isset($_POST['add_to_cart'])) {
    $product_title = $_POST['product_title'];
    $product_price = $_POST['product_price'];
    $product_meter = $_POST['product_meter'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $product_exists = false;

    foreach ($_SESSION['cart'] as $item) {
        if ($item['title'] === $product_title) {
            $product_exists = true;
            break;
        }
    }

    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'title' => $product_title,
            'price' => $product_price,
            'meter' => $product_meter,
        ];
    }

    $queryString = http_build_query($_GET);
    header("Location: ?" . $queryString);
    exit;

}

if (isset($_POST['remove'])) {
    $item_index = $_POST['item_index'];
    if (isset($_SESSION['cart'][$item_index])) {
        unset($_SESSION['cart'][$item_index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

if (isset($_POST['checkout'])) {
    echo "<script>alert('خرید نهایی شد!');</script>";
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <link rel="stylesheet" href="./index.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container justify-content-between align-items-center mx-1 mx-sm-auto ">
            <div class="text-primary">

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST" action="" style="display:inline; margin:0">
                        <button type="submit" name="logout" class="btn btn-danger">خروج از حساب کاربری</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#authModal"
                        style="font-size: 13px;">ورود / ثبت نام</button>
                <?php endif; ?>

                <!-- مودال ثبت نام و لاگین -->
                <div dir="rtl" class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header d-flex justify-content-between">
                                <h5 class="modal-title" id="authModalLabel">ورود / ثبت نام</h5>
                                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- تب‌ها -->
                                <ul class="nav nav-tabs justify-content-between p-0 mb-2" id="authTab" role="tablist">
                                    <li class="nav-item flex-grow-1" role="presentation">
                                        <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login"
                                            role="tab" aria-controls="login" aria-selected="true">ورود</a>
                                    </li>
                                    <li class="nav-item flex-grow-1" role="presentation">
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
                <!-- ثبت سفارش سبد خرید -->
                <button class="btn btn-outline-primary" style="font-size: 13px;" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#0000FF" width="15" viewBox="0 0 512 512">
                        <path
                            d="M121.263 390.737c-11.144 0-20.21-9.065-20.21-20.21V87.578c0-33.433-27.2-60.633-60.633-60.633H0v40.42h40.42c11.145 0 20.212 9.067 20.212 20.212v282.946c0 33.434 27.2 60.632 60.632 60.632h336.842v-40.42H121.263zM154.947 431.158c-14.86 0-26.947 12.09-26.947 26.947s12.09 26.947 26.947 26.947 26.947-12.09 26.947-26.947-12.088-26.947-26.947-26.947zM384 431.158c-14.86 0-26.947 12.09-26.947 26.947s12.09 26.947 26.947 26.947c14.86 0 26.947-12.09 26.947-26.947S398.86 431.158 384 431.158z">
                        </path>
                        <path d="M141.474 114.526v87.58h343.158L512 114.525M141.474 242.526v87.58h303.158l27.37-87.58">
                        </path>
                    </svg>
                    ثبت سفارش
                </button>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">سبد خرید</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- نمایش محصولات داخل سبد خرید -->
                                <?php if (!empty($_SESSION['cart'])): ?>
                                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                        <div>
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <h5><?php echo htmlspecialchars($item['title']); ?></h5>
                                                <p><?php echo htmlspecialchars($item['meter']); ?> متر</p>
                                                <h6>قیمت: <?php echo number_format($item['price']); ?> تومان</h6>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="item_index" value="<?php echo $id; ?>">
                                                    <button name="remove"
                                                        class="btn btn-danger btn-sm">حذف</button>
                                                </form>
                                            </div>
                                            <hr>
                                        </div>
                                    <?php endforeach; ?>
                                    <form method="POST" action="" class="mt-3 d-flex justify-content-center">
                                        <button class="btn btn-success" name="checkout">خرید نهایی</button>
                                    </form>
                                <?php else: ?>
                                    <p class="text-end">سبد خرید خالی است</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                    aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="#">تماس با ما</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">بلاگ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">صفحات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active text-primary" href="shop.php">خدمات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">خانه</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="position-relative d-none d-md-block" style="margin-bottom: 20px;">
                <a href="/" class="position-absolute z-1" style="right: 16px;">
                    <img src="./images/logo.png" alt="">
                </a>
                <div class="bg-white shadow-lg position-absolute end-0 -4"
                    style="width: 145px;height: 95px;top: -18px;">
                </div>
            </div>
        </div>
    </nav>

    <section style="background-image: url(./images/bg-shop.jpg);background-size: cover;background-position: center;">
        <h1 class="text-white text-center" style="padding: 130px 0;">چه چیزی نیاز دارید</h1>
    </section>

    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9 order-1 order-lg-0">
                    <div class="row">
                        <!-- نمایش محصولات -->
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="col-lg-4">
                                    <img src="./images/s-2.jpg" class="w-100" alt="">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                                        <p>متراژ : <?php echo htmlspecialchars($row['meter']); ?></p>
                                        <h6>قیمت : <?php echo number_format($row['price']); ?> تومان</h6>
                                        <form method="POST" action="">
                                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="product_title"
                                                value="<?php echo htmlspecialchars($row['title']); ?>">
                                            <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                            <input type="hidden" name="product_meter" value="<?php echo $row['meter']; ?>">
                                            <button class="btn btn-secondary" name="add_to_cart">افزودن به سبد خرید</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>هیچ محصولی موجود نیست.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12 col-lg-3 order-0 order-lg-1">
                    <form method="GET" action="">
                        <!-- فیلتر قیمت -->
                        <h5 class="text-end mb-4">فیلتر قیمت</h5>
                        <div class="position-relative w-100 ms-auto">
                            <div class="range-slider position-relative bg-secondary rounded" style="height: 5px;">
                                <input type="range" id="slider-min" name="min_price" min="0" max="1000000" value="0"
                                    oninput="document.getElementById('range-value-min').textContent = this.value;">
                                <input type="range" id="slider-max" name="max_price" min="0" max="1000000"
                                    value="1000000"
                                    oninput="document.getElementById('range-value-max').textContent = this.value;">
                            </div>
                            <div class="mt-3 text-center">
                                <span id="range-value-min">0</span> تومان -
                                <span id="range-value-max">1000000</span> تومان
                            </div>
                        </div>
                        <hr class="ms-auto border-3 border-primary" style="border-style: dashed;">

                        <!-- فیلتر متراژ -->
                        <h5 class="text-end my-4">متراژ</h5>
                        <div class="form-check d-flex justify-content-between ms-auto" dir="rtl" style="width: 150px;">
                            <input class="form-check-input" type="checkbox" name="meter[]" value="50"
                                id="flexCheckDefault" <?php echo (isset($_GET['meter']) && in_array('50', $_GET['meter'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="flexCheckDefault">کمتر از 50</label>
                        </div>
                        <div class="form-check d-flex justify-content-between ms-auto" dir="rtl" style="width: 150px;">
                            <input class="form-check-input" type="checkbox" name="meter[]" value="50-80"
                                id="flexCheckChecked1" <?php echo (isset($_GET['meter']) && in_array('50-80', $_GET['meter'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="flexCheckChecked1">بین 50 تا 80</label>
                        </div>
                        <div class="form-check d-flex justify-content-between ms-auto mb-4" dir="rtl"
                            style="width: 150px;">
                            <input class="form-check-input" type="checkbox" name="meter[]" value="100"
                                id="flexCheckChecked2" <?php echo (isset($_GET['meter']) && in_array('100', $_GET['meter'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="flexCheckChecked2">بیشتر از 100</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 bg-dark">
        <div class="container">
            <div class="row pt-5">
                <div class="col-12 col-md-4 text-white text-center">
                    <h5 class="mb-5">آخرین اخبار</h5>
                    <div>
                        <a class="text-decoration-none text-white" href="#">هر رنگ چه انرژی می دهد؟</a>
                        <p>25 بهمن</p>
                    </div>
                    <div>
                        <a class="text-decoration-none text-white" href="#">نکته هایی از نقاشی در خانه</a>
                        <p>25 بهمن</p>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-white text-center">
                    <h5 class="mb-5">لینک های سایت</h5>
                    <ul class="list-unstyled">
                        <li class="my-3">
                            <a href="#" class="text-decoration-none text-white">درباره ما</a>
                        </li>
                        <li class="my-3">
                            <a href="#" class="text-decoration-none text-white">خذمات ما</a>
                        </li>
                        <li class="my-3">
                            <a href="#" class="text-decoration-none text-white">پروژه ما</a>
                        </li>
                        <li class="my-3">
                            <a href="#" class="text-decoration-none text-white">تماس با ما</a>
                        </li>
                        <li class="my-3">
                            <a href="#" class="text-decoration-none text-white">تیم ما</a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 text-white text-end">
                    <h4 class="mb-5">هومینو</h4>
                    <p>
                        این یک متن است. برای ویرایش این متن دوبار بر روی آن کلیک کنید. شما به راحتی می توانید این متن را
                        جا به جا کنید. این یک متن است. برای ویرایش این متن دوبار بر روی آن کلیک کنید.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="./bootstrap.bundle.min.js"></script>
</body>

</html>