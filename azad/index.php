<?php
include 'logic/db_connect.php';
include 'logic/auth.php';
$isLoggedIn = isset($_SESSION['login']) && $_SESSION['login'] === true;

if (isset($_POST["comment_submitted"])) {
    if (isset($_POST["name"]) && isset($_POST["description"])) {
        $name = $_POST["name"];
        $description = $_POST["description"];

        $stmt = $conn->prepare("INSERT INTO comments (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $stmt->error;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

    } else {
        echo "لطفاً فرم را به طور کامل پر کنید.";
    }
}
if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; 
    }

    $product_exists = false;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['name'] === $product_name) {
            $product_exists = true;
            break;
        }
    }

    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'name' => $product_name,
            'price' => $product_price,
        ];
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
if (isset($_POST['remove_item'])) {
    $item_index = $_POST['item_index'];
    if (isset($_SESSION['cart'][$item_index])) {
        unset($_SESSION['cart'][$item_index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    $_SESSION['checkout_message'] = "خرید نهایی انجام شد!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_SESSION['checkout_message'])) {
    echo "<script>alert('" . $_SESSION['checkout_message'] . "');</script>";
    unset($_SESSION['checkout_message']);
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
    <nav class="navbar navbar-expand-lg bg-primary w-100 align-items-center">
        <div class="container justify-content-center">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex justify-content-center align-items-start">
                    <div class="btn-group flex-column flex-lg-row">
                        <?php if ($isLoggedIn): ?>
                            <form method="POST" action="">
                                <button type="submit" name="logout"
                                    class="btn btn-danger h-100 border-start rounded-0 px-4 my-3 my-lg-0">
                                    خروج از حساب کاربری
                                </button>
                            </form>
                        <?php else: ?>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                class="btn btn-primary border-start rounded-0 px-4 my-3 my-lg-0">
                                ورود به حساب کاربری
                            </button>
                        <?php endif; ?>

                        <!-- login modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">وارد شوید</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form dir="rtl" method="POST" action="">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">ایمیل</label>
                                                <input type="email" class="form-control" id="exampleInputEmail1"
                                                    name="email" required aria-describedby="emailHelp">
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputPassword1" class="form-label">رمز عبور</label>
                                                <input type="password" class="form-control" id="exampleInputPassword1"
                                                    name="password" required>
                                            </div>
                                            <div>
                                                <button type="submit" name="login" class="btn btn-primary">ورود</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ------------ -->
                        <button type="button" class="btn btn-primary border-start rounded-0 px-4 my-3 my-lg-0">ارتباط با
                            ما</button>
                        <div class="btn-group my-3 my-lg-0">
                            <button type="button"
                                class="btn btn-primary border-start rounded-0 px-4 d-flex flex-column gap-1 align-items-center pt-3 dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                میز خدمت
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end text-end">
                                <li><a class="dropdown-item" href="#">سامانه شفافیت</a></li>
                                <li><a class="dropdown-item" href="#">سنجش رضایت</a></li>
                            </ul>
                        </div>
                        <div class="btn-group my-3 my-lg-0">
                            <button type="button"
                                class="btn btn-primary border-start rounded-0 px-4 d-flex flex-column gap-1 align-items-center pt-3  dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                آزمون ها
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end text-end">
                                <li><a class="dropdown-item" href="#">سراسری</a></li>
                                <li><a class="dropdown-item" href="#">کاردانی</a></li>
                            </ul>
                        </div>
                        <button type="button"
                            class="btn btn-primary border-start rounded-0 px-4 my-3 my-lg-0">اخبار</button>
                        <div class="btn-group my-3 my-lg-0">
                            <button type="button"
                                class="btn btn-primary border-start rounded-0 px-4 d-flex flex-column gap-1 align-items-center pt-3  dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                معرفی سازمان
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end text-end">
                                <li><a class="dropdown-item" href="#">درباره ما</a></li>
                                <li><a class="dropdown-item" href="#">اهداف</a></li>
                            </ul>
                        </div>
                        <button type="button"
                            class="btn btn-primary border-start border-end rounded-0 px-4 my-3 my-lg-0">صفحه
                            اصلی</button>
                    </div>
                </div>
            </div>
    </nav>
    <div class="container">
        <section class="my-5">
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <div class="position-relative h-100">
                        <img src="./idk.jpg" alt="" class="w-100 h-100">
                        <p class="position-absolute bottom-0 bg-dark text-white w-100 p-2 text-center m-0">
                            فقط یکبار میشه رفت دانشگاه
                        </p>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    <div id="carouselExampleCaptions" class="carousel slide">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="./azad.jpg" class="d-block w-100" alt="azad" style="height: 450px;">
                                <div class="carousel-caption bg-dark">
                                    <h5>تخریب دانشگاه آزاد</h5>
                                    <p>امروز دانشگاه آزاد مشهد واحد الهیه تخریب شد</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="./azad.jpg" class="d-block w-100" alt="azad" style="height: 450px;">
                                <div class="carousel-caption bg-dark">
                                    <h5>تخریب دانشگاه آزاد</h5>
                                    <p>امروز دانشگاه آزاد مشهد واحد الهیه تخریب شد</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="./azad.jpg" class="d-block w-100" alt="azad" style="height: 450px;">
                                <div class="carousel-caption bg-dark">
                                    <h5>تخریب دانشگاه آزاد</h5>
                                    <p>امروز دانشگاه آزاد مشهد واحد الهیه تخریب شد</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section id="posts" class="my-5" dir="rtl">
            <form id="filter_form" method="POST" class="mb-4" action="#posts">
                <button type="submit" name="sort" value="desc" class="btn btn-outline-secondary">جدیدترین</button>
                <button type="submit" name="sort" value="asc" class="btn btn-outline-secondary">قدیمی‌ترین</button>
            </form>
            <?php include 'logic/posts.php'; ?>
        </section>

        <section class="my-5">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">کاردانی
                        فنی حرفه ای</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">کاردانی
                        به کارشناسی</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">کارشناسی
                        ارشد</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">سراسری</a>
                </div>

                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">آزمون
                        های بین الملل</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">پیام
                        نور</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">دکترای
                        تخصصی</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">جامع
                        علمی کاربردی</a>
                </div>

                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">استخدامی
                        دستگاه اجرایی</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">سایر
                        آزمون استخدامی</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">TOLIMO</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 gy-3">
                    <a href="#"
                        class="text-decoration-none border border-primary p-4 d-block w-100 text-center text-secondary">استعداد
                        های درخشان</a>
                </div>
            </div>
        </section>

        <section class="my-5">
            <div class="row mt-5">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">آزمون نظام مهندسی</h5>
                            <h5>20000 تومان</h5>
                            <form method="POST" action="">
                                <input type="hidden" name="product_name" value="آزمون نظام مهندسی">
                                <input type="hidden" name="product_price" value="20000">
                                <button type="submit" name="add_to_cart" class="btn btn-primary mt-4">
                                    افزودن به سبد خرید
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">آزمون آموزش پرورش</h5>
                            <h5>20000 تومان</h5>
                            <form method="POST" action="">
                                <input type="hidden" name="product_name" value="آزمون آموزش پرورش">
                                <input type="hidden" name="product_price" value="20000">
                                <button type="submit" name="add_to_cart" class="btn btn-primary mt-4">
                                    افزودن به سبد خرید
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">آزمون وکلا</h5>
                            <h5>40000 تومان</h5>
                            <form method="POST" action="">
                                <input type="hidden" name="product_name" value="آزمون وکلا">
                                <input type="hidden" name="product_price" value="40000">
                                <button type="submit" name="add_to_cart" class="btn btn-primary mt-4">
                                    افزودن به سبد خرید
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">آزمون کارشناسان قضایی</h5>
                            <h5>30000 تومان</h5>
                            <form method="POST" action="">
                                <input type="hidden" name="product_name" value="آزمون کارشناسان قضایی">
                                <input type="hidden" name="product_price" value="30000">
                                <button type="submit" name="add_to_cart" class="btn btn-primary mt-4">
                                    افزودن به سبد خرید
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="my-5">
            <div class="row mt-5">
                <div class="col-12 col-lg-6 gy-3">
                    <div class="row">
                        <div class="col-lg-3 flex-grow-1">
                            <div class="border h-100 pb-5 position-relative">
                                <div class="position-absolute start-50 border-bottom border-end bg-white arrow"></div>
                                <h5 class="text-primary bg-secondary-subtle text-end p-4 m-0">پاسخگویی و ارتباط با
                                    سازمان</h5>
                                <img src="./sanjesh.jpg" alt="sanjesh" class="d-block w-100">
                            </div>
                        </div>
                        <div class="col-lg-3 flex-grow-1">
                            <div class="border h-100 pb-5 position-relative">
                                <div class="position-absolute start-50 border-bottom border-end bg-white arrow"></div>
                                <h5 class="text-primary bg-secondary-subtle text-end p-4 m-0">پاسخگویی و ارتباط با
                                    سازمان</h5>
                                <img src="./sanjesh.jpg" alt="sanjesh" class="d-block w-100">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-6 gy-3">
                    <div class="border h-100 position-relative">
                        <div class="position-absolute start-50 border-bottom border-end bg-white arrow"></div>
                        <h5 class="text-primary bg-secondary-subtle text-end p-4 m-0">اطلاعیه</h5>
                        <ul class="list-group list-group-flush p-0" dir="rtl">
                            <li class="list-group-item">
                                <input class="form-check-input" type="radio" name="listGroupRadio" id="1">
                                <label class="form-check-label me-2" for="1">
                                    اطلاعیه در خصوص تمدید مهلت ثبت نام کنکور
                                </label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="radio" name="listGroupRadio" id="2">
                                <label class="form-check-label me-2" for="2">
                                    اطلاعیه در خصوص تمدید مهلت ثبت نام کنکور
                                </label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="radio" name="listGroupRadio" id="3">
                                <label class="form-check-label me-2" for="3">
                                    اطلاعیه در خصوص تمدید مهلت ثبت نام کنکور
                                </label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="radio" name="listGroupRadio" id="4">
                                <label class="form-check-label me-2" for="4">
                                    اطلاعیه در خصوص تمدید مهلت ثبت نام کنکور
                                </label>
                            </li>
                            <button class="btn btn-secondary w-25 mt-3 me-3">بعدی</button>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="my-5">
            <div class="row">
                <div class="col-12 col-lg-6 gy-3 order-1 order-lg-0">
                    <div class="border h-100 position-relative">
                        <div class="position-absolute start-50 border-bottom border-end bg-white arrow"></div>
                        <h5 class="text-primary bg-secondary-subtle text-end p-4 m-0">اطلاعیه</h5>
                        <ul class="list-group list-group-flush p-0" dir="rtl">
                            <li class="list-group-item">
                                <input class="form-check-input" type="radio" name="listGroupRadio" id="1">
                                <label class="form-check-label me-2" for="1">
                                    اطلاعیه در خصوص تمدید مهلت ثبت نام کنکور
                                </label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="radio" name="listGroupRadio" id="2">
                                <label class="form-check-label me-2" for="2">
                                    اطلاعیه در خصوص تمدید مهلت ثبت نام کنکور
                                </label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="radio" name="listGroupRadio" id="3">
                                <label class="form-check-label me-2" for="3">
                                    اطلاعیه در خصوص تمدید مهلت ثبت نام کنکور
                                </label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="radio" name="listGroupRadio" id="4">
                                <label class="form-check-label me-2" for="4">
                                    اطلاعیه در خصوص تمدید مهلت ثبت نام کنکور
                                </label>
                            </li>
                            <button class="btn btn-secondary w-25 m-3">بعدی</button>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-lg-6 gy-3">
                    <div class="list-group rounded-0">
                        <a href="#"
                            class="list-group-item list-group-item-action list-group-item-warning text-end py-3">
                            اخبار و اطلاعیه
                        </a>
                        <a href="#" class="list-group-item list-group-item-action text-end py-3">ثبت نام</a>
                        <a href="#" class="list-group-item list-group-item-action text-end py-3">اعلام نتایج</a>
                        <a href="#" class="list-group-item list-group-item-action text-end py-3">مستندات</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="my-5">
            <div class="border h-100 position-relative">
                <div class="position-absolute start-50 border-bottom border-end bg-white arrow"></div>
                <h5 class="text-primary bg-secondary-subtle text-end p-4 m-0">ارتباط با ما</h5>
                <div class="accordion accordion-flush p-2" id="accordionFlushExample" dir="rtl">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseOne" aria-expanded="false"
                                aria-controls="flush-collapseOne">
                                تماس با سازمان ملی سنجش
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <p>
                                    لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان
                                    گرافیک
                                    است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای
                                    شرایط
                                    فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد،
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                aria-controls="flush-collapseTwo">
                                تماس از طریق اینترنت
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <p>
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک
                                است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط
                                فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد،
                            </p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseThree" aria-expanded="false"
                                aria-controls="flush-collapseThree">
                                تماس از طریق تلفن
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <p>
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک
                                است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط
                                فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد،
                            </p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseFive" aria-expanded="false"
                                aria-controls="flush-collapseFive">
                                تماس از طریق مکاتبه
                            </button>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <p>
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک
                                است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط
                                فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد،
                            </p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseFour" aria-expanded="false"
                                aria-controls="flush-collapseFour">
                                تماس حضوری
                            </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <p>
                                لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک
                                است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط
                                فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد،
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="comments" class="my-5" dir="rtl">
            <h4 class="mb-3">ثبت نظر جدید</h4>
            <form method="POST" action="#comments">
                <input type="text" name="name" class="form-control w-50" placeholder="نام شما" required>
                <textarea name="description" class="form-control mt-3" rows="3" placeholder="پیام خود را بنویسید"
                    required></textarea>
                <input type="hidden" name="comment_submitted" value="1">
                <button type="submit" class="btn btn-primary mt-3">ثبت نظر</button>
            </form>
            <div class="mt-3">
                <h4>نظرات شما</h4>
                <?php include 'logic/comments.php'; ?>
            </div>
        </section>
        <button class="position-fixed border-0 bottom-0 start-0 ms-4 mb-2 badge rounded-pill text-bg-secondary p-3"
            data-bs-toggle="modal" data-bs-target="#exampleModalCart">
            <svg xmlns="http://www.w3.org/2000/svg" width="47.998" height="40.34">
                <g fill="#fff">
                    <path
                        d="M47.273 0h-6.544a.728.728 0 0 0-.712.58L38.63 7.219H.727a.727.727 0 0 0-.7.912l4.6 17.5c.006.021.019.037.026.059a.792.792 0 0 0 .042.094.747.747 0 0 0 .092.135.831.831 0 0 0 .065.068.626.626 0 0 0 .167.107.285.285 0 0 0 .045.029l13.106 5.145-5.754 2.184a4.382 4.382 0 1 0 .535 1.353l7.234-2.746 6.866 2.7A4.684 4.684 0 1 0 27.6 33.4l-5.39-2.113 13.613-5.164c.013-.006.021-.016.033-.021a.712.712 0 0 0 .188-.119.625.625 0 0 0 .063-.072.654.654 0 0 0 .095-.135.58.58 0 0 0 .04-.1.73.73 0 0 0 .033-.084l5.042-24.137h5.953a.728.728 0 0 0 0-1.455zM8.443 38.885a3.151 3.151 0 1 1 3.152-3.15 3.155 3.155 0 0 1-3.152 3.15zm23.1-6.3a3.151 3.151 0 1 1-3.143 3.149 3.155 3.155 0 0 1 3.148-3.152zM25.98 8.672l-.538 7.3H14.661l-.677-7.295zm-.645 8.75-.535 7.293h-9.328l-.672-7.293zM1.671 8.672h10.853l.677 7.3h-9.61zm2.3 8.75h9.362l.677 7.293H5.892zM20.2 30.5 9.175 26.17H31.6zm14.778-5.781h-8.722l.537-7.293h9.7zm1.822-8.752h-9.9l.537-7.295h10.889z" />
                    <circle cx="8.443" cy="35.734" r=".728" />
                    <circle cx="31.548" cy="35.734" r=".728" />
                </g>
            </svg>
        </button>

        <div class="modal fade" id="exampleModalCart" tabindex="-1" aria-labelledby="exampleModalCartLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalCartLabel">سبد خرید</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <ul class="list-group">
                                <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo htmlspecialchars($item['name']) . " - " . htmlspecialchars($item['price']) . " تومان"; ?>
                                        <form method="POST" action="">
                                            <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                                            <button type="submit" name="remove_item" class="btn btn-danger btn-sm">حذف</button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <form method="POST" action="" class="mt-3">
                                <button type="submit" name="clear_cart" class="btn btn-success">خرید نهایی</button>
                            </form>
                        <?php else: ?>
                            <p>سبد خرید خالی است.</p>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./bootstrap.bundle.min.js"></script>
</body>

</html>