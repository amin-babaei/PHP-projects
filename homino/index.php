<?php

include("./register.php");

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
                                        <form method="POST" action="" class="mt-3 d-flex justify-content-center">
                                            <button class="btn btn-success" name="checkout">خرید نهایی</button>
                                        </form>
                                    <?php endforeach; ?>
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
                                <a class="nav-link" href="shop.php">خدمات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active text-primary" href="index.php">خانه</a>
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

    <header class="bg-primary">
        <div class="w-100 h-100 position-relative overflow-hidden">
            <div class="container position-relative">
                <div class="position-absolute end-0 d-flex flex-column align-items-center" style="margin-top: 160px;">
                    <h2 class="text-white" dir="rtl">
                        هــمـه کارهـاتو به هومـــینو بِسپُر
                    </h2>
                    <h2 class="text-white" dir="rtl">
                        و از انجام دادنش لذت ببر ...
                    </h2>
                    <button type="button" class="btn btn-outline-light rounded-pill mt-3 px-4 py-2">مشاوره
                        رایگان</button>
                </div>
            </div>
            <img class="wzc51-img-inner" src="./images/header-bg.png">
            <img class="position-absolute end-50 bottom-0" src="./images/header-man.png" alt="">
        </div>
    </header>

    <section class="my-4">
        <h3 class="text-primary text-center">هومینو چه کمکی به من می کنه؟</h3>
        <h5 class="text-center text-muted">بخشی از خدمات فعال مجموعه هومینو</h5>
        <div class="container mt-5">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="text-decoration-none">
                        <div class="d-flex border flex-column align-items-center justify-content-center py-5 h-100">
                            <img src="./images/izogam.png" alt="">
                            <h4 class="mt-4 text-muted">ایزوگام و عایق</h4>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="text-decoration-none">
                        <div class="d-flex border flex-column align-items-center justify-content-center py-5 h-100">
                            <img src="./images/parket.png" alt="">
                            <h4 class="mt-4 text-muted">پارکت و سرامیک</h4>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="shop.php" class="text-decoration-none">
                        <div class="d-flex border flex-column align-items-center justify-content-center py-5 h-100">
                            <img src="./images/choob.png" alt="">
                            <h4 class="mt-4 text-muted">تعمیرات چوب</h4>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="text-decoration-none">
                        <div class="d-flex border flex-column align-items-center justify-content-center py-5 h-100">
                            <img src="./images/ashpaz.png" alt="">
                            <h4 class="mt-4 text-muted">آشپز خونه</h4>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="text-decoration-none">
                        <div class="d-flex border flex-column align-items-center justify-content-center py-5 h-100">
                            <img src="./images/naghasi.png" alt="">
                            <h4 class="mt-4 text-muted">نقاشی ساختمان</h4>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="text-decoration-none">
                        <div class="d-flex border flex-column align-items-center justify-content-center py-5 h-100">
                            <img src="./images/service.png" alt="">
                            <h4 class="mt-4 text-muted">سرویس بهداشتی</h4>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="text-decoration-none">
                        <div class="d-flex border flex-column align-items-center justify-content-center py-5 h-100">
                            <img src="./images/bargh.png" alt="">
                            <h4 class="mt-4 text-muted">تعمیرات برقی</h4>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="text-decoration-none">
                        <div class="d-flex border flex-column align-items-center justify-content-center py-5 h-100">
                            <img src="./images/loole.png" alt="">
                            <h4 class="mt-4 text-muted">لوله کشی</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="my-4"
        style="background-image: url(./images/section.png); background-repeat: no-repeat;background-size: cover;background-color: rgb(31, 113, 169);background-position: center;">
        <div class="container h-100">
            <div class="row h-100 justify-content-between" style="padding: 100px 0;">
                <div class="col-12 col-sm-6 h-100 d-flex justify-content-end align-items-center">
                    <button class="btn btn-outline-light px-5 py-3 me-5 mb-5 rounded-pill">
                        ارتباط با ما
                    </button>
                </div>
                <div class="col-12 col-sm-6" dir="rtl">
                    <div class="d-flex">
                        <h5 class="text-white">
                            با کارشناسان ما
                            <span class="h2">رایگان</span>
                            مشاوره کنید
                        </h5>
                    </div>
                    <p class="text-white w-75">قبل ار انجام هرکاری با متخصص همون حرفه به صورت کاملا رایگان صحبت کنید و
                        از راهنمایی های اون کمک بگیرید.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="my-4 py-4">
        <h3 class="text-primary text-center">آخرین کارهای انجام شده</h3>
        <h5 class="text-center text-muted">توسط کادر حرفه ای، مشتری مدار و با سابقه</h5>
        <div class="container">
            <div class="row mt-5">
                <div class="col-12 col-lg-4">
                    <img src="./images/s-1.jpg" class="w-100" alt="">
                    <p class="text-end">
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است،
                        چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی
                        مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد
                    </p>
                </div>
                <div class="col-12 col-lg-4">
                    <img src="./images/s-2.jpg" class="w-100" alt="">
                    <p class="text-end">
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است،
                        چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی
                        مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد
                    </p>
                </div>
                <div class="col-12 col-lg-4">
                    <img src="./images/s-3.jpg" class="w-100" alt="">
                    <p class="text-end">
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است،
                        چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی
                        مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-4 bg-light pt-5">
        <h3 class="text-primary text-center">ما چطور کار می کنیم؟</h3>
        <h5 class="text-center text-muted">برای استفاده از خدمات و ثبت سفارش این مراحل رو دنبال‌کن</h5>
        <div class="container">
            <div class="row mt-5 align-items-center">
                <div class="col-12 col-lg-6">
                    <img src="./images/man.png" alt="">
                </div>
                <div class="col-12 col-lg-6">
                    <div class="d-flex gap-3 justify-content-between align-items-center">
                        <div class="text-end mr-5">
                            <h5>انتخاب سرویس موردنظر</h5>
                            <p style="font-size: 13px;">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
                                استفاده از طراحان گرافیک است</p>
                        </div>
                        <div class="position-relative">
                            <div class="border border-3 border-primary rounded-circle p-5 h3">
                                1
                            </div>
                        </div>
                    </div>
                    <div class="d-flex my-4 gap-3 justify-content-between align-items-center">
                        <div class="text-end mr-5">
                            <h5>تماس و هماهنگی قبل از اعزام</h5>
                            <p style="font-size: 13px;">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
                                استفاده از طراحان گرافیک است</p>
                        </div>
                        <div class="position-relative">
                            <div class="border border-3 border-primary rounded-circle p-5 h3">
                                2
                            </div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="d-flex my-4 gap-3 justify-content-between align-items-center">
                        <div class="text-end mr-5">
                            <h5>انجام کار</h5>
                            <p style="font-size: 13px;">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
                                استفاده از طراحان گرافیک است</p>
                        </div>
                        <div class="position-relative">
                            <div class="border border-3 border-primary rounded-circle p-5 h3">
                                3
                            </div>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-primary" style="padding: 80px 0;">
        <h3 class="text-white text-center">نظرات مشتریان درباره هومینو</h3>
        <h5 class="text-center text-white">بیش از هزار مشتری خوشحال افتخاری است به آن دست یافتیم</h5>
        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators top-100">
                <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="0"
                    class="active rounded-circle" style="height: 20px;" aria-current="true"
                    aria-label="Slide 1"></button>
                <button type="button" class="rounded-circle" data-bs-target="#carouselExampleInterval"
                    data-bs-slide-to="1" aria-label="Slide 2" style="height: 20px;"></button>
                <button type="button" class="rounded-circle" data-bs-target="#carouselExampleInterval"
                    data-bs-slide-to="2" aria-label="Slide 3" style="height: 20px;"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <div class="d-flex flex-column align-items-center mt-5">
                        <img src="./images/comments.png" class="d-block" style="width: 200px;height: 200px;">
                        <div class="position-absolute text-center text-white" style="bottom: -15px;">
                            <h5>داوود</h5>
                            <p>ورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک
                                است.
                                چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <div class="d-flex flex-column align-items-center mt-5">
                        <img src="./images/comments.png" class="d-block" style="width: 200px;height: 200px;">
                        <div class="position-absolute text-center text-white" style="bottom: -15px;">
                            <h5>ممد</h5>
                            <p>ورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک
                                است.
                                چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex flex-column align-items-center mt-5">
                        <img src="./images/comments.png" class="d-block" style="width: 200px;height: 200px;">
                        <div class="position-absolute text-center text-white" style="bottom: -15px;">
                            <h5>قلی</h5>
                            <p>ورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک
                                است.
                                چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
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